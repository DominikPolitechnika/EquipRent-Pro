<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\CardException;
use Stripe\Invoice as StripeInvoice;
use Stripe\Refund as StripeRefund;
use Stripe\SetupIntent;
use Stripe\StripeClient;

class StripeService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function getOrCreateCustomer(User $user): string
    {
        if ($user->stripe_customer_id) {
            return $user->stripe_customer_id;
        }

        $customer = $this->stripe->customers->create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => ['user_id' => $user->id],
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer->id;
    }

    public function createSetupIntent(User $user): SetupIntent
    {
        $customerId = $this->getOrCreateCustomer($user);

        return $this->stripe->setupIntents->create([
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'usage' => 'off_session',
        ]);
    }

    public function savePaymentMethod(User $user, string $paymentMethodId): PaymentMethodModel
    {
        $customerId = $this->getOrCreateCustomer($user);

        $pm = $this->stripe->paymentMethods->retrieve($paymentMethodId);

        if ($pm->customer !== $customerId) {
            $pm = $this->stripe->paymentMethods->attach($paymentMethodId, [
                'customer' => $customerId,
            ]);
        }

        return PaymentMethodModel::updateOrCreate(
            ['stripe_payment_method_id' => $pm->id],
            [
                'user_id' => $user->id,
                'brand' => $pm->card->brand ?? null,
                'last4' => $pm->card->last4 ?? null,
                'exp_month' => $pm->card->exp_month ?? null,
                'exp_year' => $pm->card->exp_year ?? null,
                'is_active' => true,
                'name' => $pm->card->name ?? null,
                'surname' => $pm->card->surname ?? null,
            ]
        );
    }

    public function deactivatePaymentMethod(User $user, PaymentMethodModel $paymentMethod): void
    {
        abort_unless($paymentMethod->user_id === $user->id, 403);

        try {
            $this->stripe->paymentMethods->detach($paymentMethod->stripe_payment_method_id);
        } catch (Exception $e) {
            Log::warning('Nie udało się odpiąć karty w Stripe: '.$e->getMessage());
        }

        $paymentMethod->update(['is_active' => false]);
    }

    /**
     * @param  int
     * @param  string  
     * @param  string|null
     */
    public function charge(
        User $user,
        int $amount,
        string $currency,
        string $description,
        PaymentMethodModel $paymentMethod,
        int $reservationId,
        string $idempotencyKey,
        bool $offSession,
        ?string $buyerNip = null,
    ): Payment {
        $existing = Payment::where('idempotency_key', $idempotencyKey)->first();
        if ($existing) {
            return $existing;
        }

        $customerId = $this->getOrCreateCustomer($user);

        try {
            $payment = Payment::create([
                'userId' => $user->id,
                'gatewayId' => (int) config('services.stripe.gateway_id'),
                'reservationID' => $reservationId,
                'totalPrice' => $amount,
                'status' => Payment::STATUS_PENDING,
                'payment_method_id' => $paymentMethod->id,
                'payment_type' => $offSession ? Payment::TYPE_OFF_SESSION : Payment::TYPE_ONE_TIME,
                'idempotency_key' => $idempotencyKey,
            ]);
        } catch (QueryException $e) {
            if ($this->isUniqueConstraintViolation($e)) {
                return Payment::where('idempotency_key', $idempotencyKey)->firstOrFail();
            }

            throw $e;
        }

        $invoiceItemId = null;

        try {
            $invoiceItem = $this->stripe->invoiceItems->create([
                'customer' => $customerId,
                'amount' => $amount,
                'currency' => $currency,
                'description' => $description,
            ], ['idempotency_key' => $idempotencyKey.':item']);
            $invoiceItemId = $invoiceItem->id;

            $stripeInvoice = $this->stripe->invoices->create([
                'customer' => $customerId,
                'collection_method' => 'charge_automatically',
                'auto_advance' => true,
                'default_payment_method' => $paymentMethod->stripe_payment_method_id,
                'footer' => config('services.stripe.fake_seller_footer'),
                'custom_fields' => $this->buildCustomFields($buyerNip),
            ], ['idempotency_key' => $idempotencyKey.':invoice']);

            $invoiceItemId = null;

            $stripeInvoice = $this->stripe->invoices->finalizeInvoice(
                $stripeInvoice->id,
                [],
                ['idempotency_key' => $idempotencyKey.':finalize']
            );

            $stripeInvoice = $this->stripe->invoices->pay(
                $stripeInvoice->id,
                ['off_session' => $offSession],
                ['idempotency_key' => $idempotencyKey.':pay']
            );

            $payment->update([
                'status' => Payment::STATUS_SUCCEEDED,
                'stripe_payment_intent_id' => $stripeInvoice->payment_intent,
                'paid_at' => now(),
            ]);
        } catch (CardException $e) {
            // requires_action (np. 3DS przy off-session) albo odrzucona karta
            $status = $e->getStripeCode() === 'authentication_required'
                ? Payment::STATUS_REQUIRES_ACTION
                : Payment::STATUS_FAILED;

            $payment->update([
                'status' => $status,
                'stripe_payment_intent_id' => $e->getError()?->payment_intent?->id,
            ]);

            Log::warning('Płatność Stripe nieudana (karta odrzucona)', [
                'payment_id' => $payment->id,
                'decline_code' => $e->getDeclineCode(),
                'stripe_code' => $e->getStripeCode(),
            ]);
        } catch (ApiErrorException $e) {
            if ($invoiceItemId) {
                $this->cleanupOrphanedInvoiceItem($invoiceItemId);
            }

            $payment->update(['status' => Payment::STATUS_FAILED]);

            Log::error('Błąd komunikacji ze Stripe podczas płatności', [
                'payment_id' => $payment->id,
                'message' => $e->getMessage(),
                'stripe_code' => method_exists($e, 'getStripeCode') ? $e->getStripeCode() : null,
            ]);
        } catch (Exception $e) {
            $payment->update(['status' => Payment::STATUS_FAILED]);
            Log::error('Nieoczekiwany błąd podczas płatności', [
                'payment_id' => $payment->id,
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }

        return $payment->fresh();
    }

    protected function isUniqueConstraintViolation(QueryException $e): bool
    {
        return $e->getCode() === '23505'; //kod dla unique violation w postgres
    }

    protected function cleanupOrphanedInvoiceItem(string $invoiceItemId): void
    {
        try {
            $this->stripe->invoiceItems->delete($invoiceItemId);
        } catch (Exception $e) {
            Log::error('Nie udało się posprzątać osieroconej pozycji faktury', [
                'invoice_item_id' => $invoiceItemId,
                'message' => $e->getMessage(),
            ]);
        }
    }

    protected function buildCustomFields(?string $buyerNip): array
    {
        $fields = [];

        if ($sellerNip = config('services.stripe.fake_seller_nip')) {
            $fields[] = ['name' => 'NIP sprzedawcy', 'value' => $sellerNip];
        }

        if ($buyerNip) {
            $fields[] = ['name' => 'NIP nabywcy', 'value' => $buyerNip];
        }

        return $fields;
    }

    public function getInvoiceDetails(Payment $payment): ?StripeInvoice
    {
        if (! $payment->stripe_payment_intent_id) {
            return null;
        }

        try {
            $intent = $this->stripe->paymentIntents->retrieve($payment->stripe_payment_intent_id);

            if (! $intent->invoice) {
                return null;
            }

            return $this->stripe->invoices->retrieve($intent->invoice);
        } catch (ApiErrorException $e) {
            Log::error('Nie udało się pobrać faktury ze Stripe', [
                'payment_id' => $payment->id,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * @param  int|null
     * @param  string|null
     * @throws PaymentFailedException
     */
    public function refund(
        Payment $payment,
        ?int $amount,
        ?string $reason,
        string $idempotencyKey,
    ): Payment {
        if (! in_array($payment->status, [Payment::STATUS_SUCCEEDED, Payment::STATUS_PARTIALLY_REFUNDED], true)) {
            throw new PaymentFailedException(
                "Płatność #{$payment->id} nie jest w stanie kwalifikującym się do zwrotu (status: {$payment->status}).",
                errorCode: 'invalid_status',
            );
        }

        if (! $payment->stripe_payment_intent_id) {
            throw new PaymentFailedException(
                "Płatność #{$payment->id} nie ma zapisanego stripe_payment_intent_id.",
                errorCode: 'invalid_status',
            );
        }

        $alreadyRefunded = (int) $payment->refunded_amount;
        $refundable = $payment->totalPrice - $alreadyRefunded;

        if ($refundable <= 0) {
            throw new PaymentFailedException(
                "Płatność #{$payment->id} została już zwrócona w całości.",
                errorCode: 'already_refunded',
            );
        }

        $amount ??= $refundable;

        if ($amount > $refundable) {
            throw new PaymentFailedException(
                "Kwota zwrotu ({$amount}) przekracza możliwą do zwrócenia pozostałość ({$refundable}).",
                errorCode: 'amount_exceeds_refundable',
            );
        }

        $refundIdempotencyKey = 'refund:'.$idempotencyKey;

        try {
            /** @var StripeRefund $stripeRefund */
            $stripeRefund = $this->stripe->refunds->create([
                'payment_intent' => $payment->stripe_payment_intent_id,
                'amount' => $amount,
                'reason' => $reason ?? 'requested_by_customer',
            ], ['idempotency_key' => $refundIdempotencyKey]);
        } catch (ApiErrorException $e) {
            Log::error('Zwrot środków nieudany', [
                'payment_id' => $payment->id,
                'amount' => $amount,
                'message' => $e->getMessage(),
                'stripe_code' => method_exists($e, 'getStripeCode') ? $e->getStripeCode() : null,
            ]);

            throw new PaymentFailedException(
                'Zwrot środków nie powiódł się: '.$e->getMessage(),
                errorCode: 'stripe_error',
                previous: $e,
            );
        }

        $newRefundedAmount = $alreadyRefunded + $stripeRefund->amount;

        $payment->update([
            'refunded_amount' => $newRefundedAmount,
            'stripe_refund_id' => $stripeRefund->id,
            'status' => $newRefundedAmount >= $payment->totalPrice
                ? Payment::STATUS_REFUNDED
                : Payment::STATUS_PARTIALLY_REFUNDED,
        ]);

        return $payment->fresh();
    }
}

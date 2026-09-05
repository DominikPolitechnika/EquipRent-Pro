<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\Reservation;
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

    public function savePaymentMethod(User $user, string $paymentMethodId, bool $save = true): PaymentMethodModel
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
                'cardholder_name' => $pm->billing_details->name ?? null,
                'exp_month' => $pm->card->exp_month ?? null,
                'exp_year' => $pm->card->exp_year ?? null,
                'is_active' => true,
                'is_saved' => $save,
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
                'pending_invoice_items_behavior' => 'include',
                'auto_advance' => false,
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
                'stripe_invoice_id' =>$stripeInvoice->id,
                'paid_at' => now(),
            ]);

            $this->activateReservationIfAwaitingPayment($payment);
        } catch (CardException $e) {
            $status = $e->getStripeCode() === 'authentication_required'
                ? Payment::STATUS_REQUIRES_ACTION
                : Payment::STATUS_FAILED;

            $payment->update([
                'status' => $status,
                'stripe_payment_intent_id' => $e->getError()?->payment_intent?->id,
            ]);

            if ($status === Payment::STATUS_FAILED) {
                $this->voidInvoiceIfPossible($stripeInvoice ?? null);
            }

            Log::warning('Płatność Stripe nieudana (karta odrzucona)', [
                'payment_id' => $payment->id,
                'decline_code' => $e->getDeclineCode(),
                'stripe_code' => $e->getStripeCode(),
            ]);
        } catch (ApiErrorException $e) {
            if (str_contains(strtolower($e->getMessage()), 'already paid') && isset($stripeInvoice)) {
                try {
                    $freshInvoice = $this->stripe->invoices->retrieve($stripeInvoice->id);
                } catch (Exception) {
                    $freshInvoice = null;
                }

                if ($freshInvoice && $freshInvoice->status === 'paid') {
                    $payment->update([
                        'status' => Payment::STATUS_SUCCEEDED,
                        'stripe_payment_intent_id' => $freshInvoice->payment_intent,
                        'paid_at' => now(),
                    ]);
                    $this->activateReservationIfAwaitingPayment($payment);

                    Log::warning('Faktura była już opłacona przy próbie jawnego invoices->pay() — potwierdzono sukces po sprawdzeniu statusu.', [
                        'payment_id' => $payment->id,
                        'invoice_id' => $freshInvoice->id,
                    ]);

                    return $payment->fresh();
                }
            }

            if ($invoiceItemId) {
                $this->cleanupOrphanedInvoiceItem($invoiceItemId);
            }
            $this->voidInvoiceIfPossible($stripeInvoice ?? null);

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

    public function confirmThreeDsStub(Payment $payment, bool $approve): Payment
    {
        abort_unless($payment->status === Payment::STATUS_REQUIRES_ACTION, 409,
            'Ta płatność nie oczekuje na potwierdzenie 3D Secure.');

        $invoice = $this->getInvoiceDetails($payment);
        $this->voidInvoiceIfPossible($invoice);

        $payment->update([
            'status' => $approve ? Payment::STATUS_SUCCEEDED : Payment::STATUS_FAILED,
            'paid_at' => $approve ? now() : null,
        ]);

        if ($approve) {
            $this->activateReservationIfAwaitingPayment($payment);
        }

        return $payment->fresh();
    }

    public function activateReservationIfAwaitingPayment(Payment $payment): void
    {
        if (! $payment->reservationID) {
            return;
        }

        $reservation = Reservation::find($payment->reservationID);

        if ($reservation && $reservation->statusOfReservation === 'awaiting_payment') {
            $reservation->update(['statusOfReservation' => 'active']);
        }
    }

    public function listOpenPenaltyInvoices(User $user): array
    {
        if (! $user->stripe_customer_id) {
            return [];
        }

        try {
            $invoices = $this->stripe->invoices->all([
                'customer' => $user->stripe_customer_id,
                'status' => 'open',
                'limit' => 100,
            ]);
        } catch (ApiErrorException $e) {
            Log::warning('Nie udało się pobrać otwartych faktur (kar) ze Stripe.', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return [];
        }

        $penalties = [];

        foreach ($invoices->data as $invoice) {
            $reservationId = $invoice->metadata['reservation_id'] ?? null;

            if ($reservationId === null || $reservationId === '') {
                continue;
            }

            $penalties[] = [
                'reservation_id' => (int) $reservationId,
                'invoice_id' => $invoice->id,
                'amount_due' => $invoice->amount_due,
                'currency' => strtoupper($invoice->currency),
                'description' => $invoice->description,
                'hosted_invoice_url' => $invoice->hosted_invoice_url,
                'invoice_pdf' => $invoice->invoice_pdf,
                'due_date' => $invoice->due_date,
            ];
        }

        return $penalties;
    }

    protected function voidInvoiceIfPossible(?StripeInvoice $invoice): void
    {
        if (! $invoice) {
            return;
        }

        try {
            if ($invoice->status === 'draft') {
                $this->stripe->invoices->delete($invoice->id);
            } elseif ($invoice->status === 'open') {
                $this->stripe->invoices->voidInvoice($invoice->id);
            }
        } catch (Exception $e) {
            Log::warning('Nie udało się zamknąć porzuconej faktury w Stripe.', [
                'invoice_id' => $invoice->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    protected function isUniqueConstraintViolation(QueryException $e): bool
    {
        // 23505 = unique_violation w Postgresie
        return $e->getCode() === '23505';
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
    if ($payment->stripe_invoice_id) {
        try {
            return $this->stripe->invoices->retrieve($payment->stripe_invoice_id);
        } catch (ApiErrorException $e) {
            return null;
        }
    }

    if (!$payment->stripe_payment_intent_id) {
        return null;
    }

    try {
        $intent = $this->stripe->paymentIntents->retrieve(
            $payment->stripe_payment_intent_id,
            ['expand' => ['invoice']]
        );

        if (!$intent->invoice) {
            return null;
        }

        return is_object($intent->invoice) 
            ? $intent->invoice 
            : $this->stripe->invoices->retrieve($intent->invoice);
    } catch (ApiErrorException $e) {
        Log::error('Nie udało się pobrać faktury ze Stripe', [
            'payment_id' => $payment->id,
            'message' => $e->getMessage(),
        ]);

        return null;
    }
}

    public function refund(
        Payment $payment,
        ?int $amount,
        ?string $reason,
        string $idempotencyKey,): Payment 
    {
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

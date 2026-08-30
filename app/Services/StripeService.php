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

    /**
     * @param  bool  $save  true = użytkownik świadomie zapisuje kartę do
     *                      przyszłych płatności (widoczna na liście, liczy
     *                      się do limitu 3 zapisanych kart). false = karta
     *                      użyta jednorazowo z widoku płatności — nadal
     *                      podpięta w Stripe (żeby dało się nią obciążyć
     *                      przez Invoicing API), ale ukryta i nie liczy się
     *                      do limitu.
     */
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
                // billing_details.name pochodzi z tego, co front-end podał
                // w stripe.confirmCardSetup({ payment_method: { billing_details: { name: ... } } }).
                // Stripe zapisuje to jako część obiektu PaymentMethod, więc
                // wystarczy je tu odczytać — nie trzeba przekazywać
                // osobnym parametrem.
                'cardholder_name' => $pm->billing_details->name ?? null,
                'exp_month' => $pm->card->exp_month ?? null,
                'exp_year' => $pm->card->exp_year ?? null,
                'is_active' => true,
                'is_saved' => $save,
            ]
        );
    }

    /**
     * "Usunięcie" karty = odpięcie w Stripe + oznaczenie is_active=false
     * lokalnie (rekord zostaje w bazie ze względu na powiązane payments).
     */
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
     * Realizuje płatność (jednorazową lub off-session) przez Stripe
     * Invoicing i zapisuje/aktualizuje wiersz w tabeli `payments`.
     *
     * @param  int  $amount  totalPrice w groszach
     * @param  string  $idempotencyKey  unikalny klucz z Twojej kolumny payments.idempotency_key
     * @param  string|null  $buyerNip  opcjonalny, symulowany NIP nabywcy do wyświetlenia na fakturze
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
        // Idempotencja na poziomie aplikacji: jeśli ten sam klucz już
        // istnieje, zwracamy istniejący wynik zamiast obciążać ponownie.
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
            // Wyścig: dwa równoległe żądania z tym samym idempotency_key.
            // Unikalny indeks w bazie (patrz migracja) złapał duplikat —
            // ktoś inny już tworzy/utworzył ten rekord, więc go zwracamy.
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
                // WAŻNE: auto_advance = false. Przy true Stripe sam, w tle,
                // próbuje automatycznie pobrać płatność zaraz po
                // finalizeInvoice() (bo collection_method jest
                // charge_automatically) — w praktyce (zwłaszcza w trybie
                // testowym) zdąża to zrobić ZANIM nasz kod dojdzie do
                // jawnego invoices->pay() kilka linijek niżej, co kończy
                // się błędem "Invoice is already paid" przy każdej próbie.
                // Z auto_advance = false to WYŁĄCZNIE nasze jawne pay()
                // poniżej obciąża kartę — dzięki temu dostajemy też
                // synchroniczny wyjątek CardException (odrzucona karta /
                // wymagane 3DS) zamiast dowiadywać się o wyniku dopiero
                // z webhooka.
                'auto_advance' => false,
                'default_payment_method' => $paymentMethod->stripe_payment_method_id,
                'footer' => config('services.stripe.fake_seller_footer'),
                'custom_fields' => $this->buildCustomFields($buyerNip),
            ], ['idempotency_key' => $idempotencyKey.':invoice']);

            // Od tego momentu pozycja faktury nie jest już "osierocona" —
            // należy do utworzonej faktury, więc nie trzeba jej czyścić
            // nawet jeśli finalize/pay dalej zawiodą.
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

            // Płatność zakończona sukcesem bez potrzeby dodatkowej
            // autoryzacji (3DS) — od razu odblokuj rezerwację, jeśli
            // czekała na płatność. Gdy Stripe zażąda 3DS, status
            // requires_action jest obsługiwany niżej i rezerwacja zostaje
            // aktywowana dopiero po confirmThreeDsStub().
            $this->activateReservationIfAwaitingPayment($payment);
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
            // Siatka bezpieczeństwa: gdyby (mimo auto_advance=false) Stripe
            // i tak zdążył opłacić fakturę zanim doszliśmy do naszego
            // jawnego invoices->pay() — np. przy dużym obciążeniu albo
            // retry po naszej stronie — nie chcemy błędnie oznaczyć udanej
            // płatności jako "failed". Sprawdzamy wtedy realny status
            // faktury w Stripe i, jeśli faktycznie jest opłacona,
            // traktujemy to jak sukces.
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

            // Timeout, rate limit, błąd konfiguracji, 5xx po stronie Stripe
            // itp. — cokolwiek innego niż odrzucenie karty. Sprzątamy
            // osieroconą pozycję faktury, jeśli invoice się nie utworzył.
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
            // Cokolwiek nieprzewidzianego (np. wyjątek z Twojej własnej
            // logiki) — rekord nie może zostać w stanie "pending" na
            // zawsze, więc oznaczamy jako failed i przepuszczamy wyjątek
            // dalej, żeby request faktycznie zwrócił 500 i trafił do logów.
            $payment->update(['status' => Payment::STATUS_FAILED]);
            Log::error('Nieoczekiwany błąd podczas płatności', [
                'payment_id' => $payment->id,
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }

        return $payment->fresh();
    }

    /**
     * ZAŚLEPKA (stub) 3D Secure — projekt nigdy nie działa produkcyjnie
     * ("aplikacja nigdy nie będzie live"), więc zamiast prawdziwej
     * autoryzacji 3DS po stronie Stripe/banku, front-end pokazuje własny
     * modal symulujący ekran weryfikacji, a to wywołanie tylko ręcznie
     * "domyka" płatność w naszej bazie jako succeeded/failed.
     *
     * NIGDY nie używać takiego mechanizmu na produkcji — prawdziwa
     * płatność musi zostać potwierdzona przez Stripe (PaymentIntent /
     * webhook), inaczej można "zapłacić" bez faktycznego obciążenia karty.
     */
    public function confirmThreeDsStub(Payment $payment, bool $approve): Payment
    {
        abort_unless($payment->status === Payment::STATUS_REQUIRES_ACTION, 409,
            'Ta płatność nie oczekuje na potwierdzenie 3D Secure.');

        $payment->update([
            'status' => $approve ? Payment::STATUS_SUCCEEDED : Payment::STATUS_FAILED,
            'paid_at' => $approve ? now() : null,
        ]);

        if ($approve) {
            $this->activateReservationIfAwaitingPayment($payment);
        }

        return $payment->fresh();
    }

    /**
     * Po udanej płatności "on-session" (widok płatności klienta) rezerwacja
     * przechodzi z "awaiting_payment" na "active" — to samo słownictwo
     * statusów, którym posługuje się App\Http\Controllers\Api\ReservationController
     * (moduł api_rezerwacje).
     */
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

    protected function isUniqueConstraintViolation(QueryException $e): bool
    {
        // 23505 = unique_violation w Postgresie
        return $e->getCode() === '23505';
    }

    /**
     * Usuwa (voiduje) pozycję faktury utworzoną tuż przed niepowodzeniem
     * dalszej części flow — inaczej doklei się do kolejnej, przyszłej
     * faktury tego samego klienta.
     */
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

    /**
     * Zbudowanie symulowanych pól VAT/NIP widocznych na fakturze PDF.
     * Nic tu nie jest walidowane ani zgłaszane do żadnego urzędu — to
     * czysto kosmetyczne pola tekstowe (max 4 pola po Stripe API).
     */
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

    /**
     * Pobiera z Stripe hosted_invoice_url / invoice_pdf dla danej płatności
     * na żądanie — nie trzymamy tego lokalnie, bo nie ma na to kolumn
     * w tabeli payments. Wymaga zapisanego stripe_payment_intent_id.
     */
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
     * Zwrot środków (pełny lub częściowy) dla opłaconej płatności —
     * typowo wywoływane przy anulowaniu rezerwacji.
     *
     * @param  int|null  $amount  kwota zwrotu w groszach; null = pełny zwrot pozostałej kwoty
     * @param  string|null  $reason  jeden z: duplicate, fraudulent, requested_by_customer
     *
     * @throws PaymentFailedException  gdy płatność nie kwalifikuje się do zwrotu
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

        // Idempotencja zwrotu: osobny "namespace" klucza niż płatność,
        // żeby nie kolidował z idempotency_key samej płatności.
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

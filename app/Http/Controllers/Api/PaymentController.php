<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Reservation;
use App\Services\PaymentFailedException;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Maksymalna liczba kart, które użytkownik może mieć jednocześnie
     * zapisanych ("is_saved" = true) do przyszłych płatności.
     */
    private const MAX_SAVED_PAYMENT_METHODS = 3;

    public function __construct(protected StripeService $stripeService) {}

    public function createSetupIntent(Request $request)
    {
        $setupIntent = $this->stripeService->createSetupIntent($request->user());

        return response()->json(['client_secret' => $setupIntent->client_secret]);
    }

    /**
     * Zapisuje/podpina kartę (payment_method_id ze Stripe.js, np. po
     * stripe.confirmCardSetup()). `save`=true (domyślnie) oznacza trwały
     * zapis widoczny w profilu i liczący się do limitu
     * MAX_SAVED_PAYMENT_METHODS. `save`=false — karta jednorazowa użyta
     * z widoku płatności bez zaznaczenia "zapisz kartę": nadal podpięta
     * w Stripe (wymagane do obciążenia przez Invoicing API), ale ukryta
     * i nie wliczana do limitu.
     */
    public function storePaymentMethod(Request $request)
    {
        $data = $request->validate([
            'payment_method_id' => ['required', 'string'],
            'save' => ['sometimes', 'boolean'],
        ]);

        $save = $data['save'] ?? true;

        if ($save) {
            $savedCount = $request->user()->paymentMethods()
                ->where('is_active', true)
                ->where('is_saved', true)
                ->count();

            if ($savedCount >= self::MAX_SAVED_PAYMENT_METHODS) {
                return response()->json([
                    'message' => 'Osiągnięto limit '.self::MAX_SAVED_PAYMENT_METHODS.' zapisanych metod płatności. Usuń jedną z istniejących kart, aby dodać nową.',
                    'error_code' => 'payment_methods_limit_reached',
                ], 409);
            }
        }

        $paymentMethod = $this->stripeService->savePaymentMethod(
            $request->user(),
            $data['payment_method_id'],
            $save,
        );

        return response()->json($paymentMethod, 201);
    }

    public function listPaymentMethods(Request $request)
    {
        return response()->json(
            $request->user()->paymentMethods()
                ->where('is_active', true)
                ->where('is_saved', true)
                ->latest('id')
                ->get()
        );
    }

    public function destroyPaymentMethod(Request $request, PaymentMethod $paymentMethod)
    {
        $this->stripeService->deactivatePaymentMethod($request->user(), $paymentMethod);

        return response()->json(null, 204);
    }

    /**
     * Jednorazowa płatność "on-session" powiązana z rezerwacją.
     * Klient wskazuje konkretną (swoją) zapisaną kartę payment_method_id
     * (id z tabeli payment_methods, nie ID ze Stripe).
     */
    public function charge(Request $request)
    {
        $data = $request->validate([
            'reservation_id' => ['required', 'integer'],
            // Kwota od klienta traktowana jest wyłącznie jako sanity-check
            // (żeby wychwycić rozjazd frontend/backend), NIGDY jako źródło
            // prawdy — poniżej i tak nadpisujemy ją ceną z `reservations`.
            'amount' => ['nullable', 'integer', 'min:50'],
            'currency' => ['required', 'string', 'size:3'],
            'description' => ['required', 'string', 'max:255'],
            'payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
            'buyer_nip' => ['nullable', 'string', 'max:20'],
            'idempotency_key' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        // Autorytatywne źródło ceny i własności — kolumny zgodne z
        // rzeczywistą tabelą `reservation` (patrz api_rezerwacje/ReservationController
        // oraz app/Models/Reservation.php): userId / totalPrice / statusOfReservation.
        $reservation = Reservation::where('id', $data['reservation_id'])
            ->where('userId', $user->id)
            ->firstOrFail();

        abort_if($reservation->statusOfReservation !== 'awaiting_payment', 409, 'Ta rezerwacja nie oczekuje na płatność.');

        // UWAGA na jednostki: `reservation.totalPrice` jest przechowywane
        // i wyświetlane w całych złotówkach (tak liczy ReservationController::store
        // w module api_rezerwacje oraz każdy widok — np. "240,00 PLN"), a
        // StripeService::charge() oczekuje kwoty w groszach (najmniejsza
        // jednostka waluty, wymóg Stripe API). Konwersja PLN -> grosze
        // następuje właśnie tutaj, w jednym, jawnie udokumentowanym miejscu.
        $amount = (int) round($reservation->totalPrice * 100);

        if (isset($data['amount']) && $data['amount'] !== $amount) {
            // Rozjazd między tym co wysłał klient a ceną w bazie — nie
            // blokujemy (i tak używamy $amount z bazy), ale logujemy jako
            // potencjalną próbę manipulacji albo bug we frontendzie.
            Log::warning('Rozbieżność kwoty przy płatności za rezerwację', [
                'user_id' => $user->id,
                'reservation_id' => $reservation->id,
                'client_amount' => $data['amount'],
                'authoritative_amount' => $amount,
            ]);
        }

        $paymentMethod = PaymentMethod::where('id', $data['payment_method_id'])
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->firstOrFail();

        $payment = $this->stripeService->charge(
            user: $user,
            amount: $amount,
            currency: strtolower($data['currency']),
            description: $data['description'],
            paymentMethod: $paymentMethod,
            reservationId: $reservation->id,
            idempotencyKey: $data['idempotency_key'] ?? (string) Str::uuid(),
            offSession: false,
            buyerNip: $data['buyer_nip'] ?? null,
        );

        return response()->json(
            $payment,
            $payment->status === Payment::STATUS_SUCCEEDED ? 201 : 402
        );
    }

    /**
     * Obciążenie zapisanej wcześniej karty bez udziału użytkownika.
     * Wywoływane np. z zadania w kolejce / komendy artisan (rozliczenie
     * rezerwacji, opłata za brak stawiennictwa itp.).
     *
     * UWAGA: `amount` tutaj musi być podane w groszach (najmniejsza
     * jednostka Stripe) — w przeciwieństwie do `reservation.totalPrice`,
     * które w module rezerwacji jest przechowywane w całych PLN. Jeśli
     * kwota pochodzi z reservation.totalPrice, pamiętaj o przeliczeniu
     * (* 100), tak jak robi to charge() powyżej.
     */
    public function chargeOffSession(Request $request)
    {
        $data = $request->validate([
            'reservation_id' => ['required', 'integer'],
            'amount' => ['required', 'integer', 'min:50'],
            'currency' => ['required', 'string', 'size:3'],
            'description' => ['required', 'string', 'max:255'],
            'payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
            'idempotency_key' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        $paymentMethod = PaymentMethod::where('id', $data['payment_method_id'])
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->firstOrFail();

        $payment = $this->stripeService->charge(
            user: $user,
            amount: $data['amount'],
            currency: strtolower($data['currency']),
            description: $data['description'],
            paymentMethod: $paymentMethod,
            reservationId: $data['reservation_id'],
            idempotencyKey: $data['idempotency_key'] ?? (string) Str::uuid(),
            offSession: true,
        );

        return response()->json(
            $payment,
            $payment->status === Payment::STATUS_SUCCEEDED ? 201 : 402
        );
    }

    /**
     * ZAŚLEPKA 3D Secure — patrz komentarz w StripeService::confirmThreeDsStub().
     * Wywoływane przez front-end po tym, jak użytkownik "zatwierdzi" albo
     * "odrzuci" w naszym własnym (nie-Stripe'owym) modalu symulującym
     * ekran weryfikacji banku.
     */
    public function confirmThreeDsStub(Request $request, Payment $payment)
    {
        abort_unless($payment->userId === $request->user()->id, 403);

        $data = $request->validate([
            'approve' => ['required', 'boolean'],
        ]);

        $payment = $this->stripeService->confirmThreeDsStub($payment, $data['approve']);

        return response()->json(
            $payment,
            $payment->status === Payment::STATUS_SUCCEEDED ? 200 : 402
        );
    }

    public function listPayments(Request $request)
    {
        return response()->json(
            $request->user()
                ->payments()
                ->orderByDesc('id')
                ->paginate(20)
        );
    }

    /**
     * Faktura (hosted_invoice_url + invoice_pdf) pobierana na żądanie
     * bezpośrednio ze Stripe, bo tabela payments tych pól nie przechowuje.
     */
    public function invoice(Request $request, Payment $payment)
    {
        abort_unless($payment->userId === $request->user()->id, 403);

        $invoice = $this->stripeService->getInvoiceDetails($payment);

        if (! $invoice) {
            return response()->json(['message' => 'Faktura niedostępna dla tej płatności.'], 404);
        }

        return response()->json([
            'hosted_invoice_url' => $invoice->hosted_invoice_url,
            'invoice_pdf' => $invoice->invoice_pdf,
            'status' => $invoice->status,
        ]);
    }

    /**
     * Zwrot środków przy anulowaniu rezerwacji. `amount` opcjonalne —
     * brak = pełny zwrot pozostałej (jeszcze niezwróconej) kwoty.
     *
     * Autoryzacja: App\Policies\PaymentPolicy::refund() — domyślnie tylko
     * role admin/staff (patrz komentarz w tej klasie co do ew. samoobsługi
     * klienta). To NIE jest publiczny endpoint dla zwykłego użytkownika.
     */
    public function refund(Request $request, Payment $payment)
    {
        $this->authorize('refund', $payment);

        $data = $request->validate([
            'amount' => ['nullable', 'integer', 'min:1'],
            'reason' => ['nullable', 'in:duplicate,fraudulent,requested_by_customer'],
            'idempotency_key' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $payment = $this->stripeService->refund(
                payment: $payment,
                amount: $data['amount'] ?? null,
                reason: $data['reason'] ?? null,
                idempotencyKey: $data['idempotency_key'] ?? (string) Str::uuid(),
            );
        } catch (PaymentFailedException $e) {
            return response()->json(
                ['message' => $e->getMessage(), 'error_code' => $e->errorCode],
                $e->httpStatus()
            );
        }

        return response()->json($payment);
    }
}

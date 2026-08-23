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
    public function __construct(protected StripeService $stripeService) {}

    public function createSetupIntent(Request $request)
    {
        $setupIntent = $this->stripeService->createSetupIntent($request->user());

        return response()->json(['client_secret' => $setupIntent->client_secret]);
    }

    public function storePaymentMethod(Request $request)
    {
        $data = $request->validate([
            'payment_method_id' => ['required', 'string'],
        ]);

        $paymentMethod = $this->stripeService->savePaymentMethod(
            $request->user(),
            $data['payment_method_id'],
        );

        return response()->json($paymentMethod, 201);
    }

    public function listPaymentMethods(Request $request)
    {
        return response()->json(
            $request->user()->paymentMethods()->where('is_active', true)->latest('id')->get()
        );
    }

    public function destroyPaymentMethod(Request $request, PaymentMethod $paymentMethod)
    {
        $this->stripeService->deactivatePaymentMethod($request->user(), $paymentMethod);

        return response()->json(null, 204);
    }

    public function charge(Request $request)
    {
        $data = $request->validate([
            'reservation_id' => ['required', 'integer'],
            'amount' => ['nullable', 'integer', 'min:1'],//kwota wykorzystywana jedynie do walidacji
            'currency' => ['required', 'string', 'size:3'],
            'description' => ['required', 'string', 'max:255'],
            'payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
            'buyer_nip' => ['nullable', 'string', 'max:20'],
            'idempotency_key' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        $reservation = Reservation::where('id', $data['reservation_id'])
            ->where('userId', $user->id)
            ->firstOrFail();

        abort_if($reservation->status !== 'awaiting_payment', 409, 'Ta rezerwacja nie oczekuje na płatność.');

        $amount = (int) $reservation->total_price;

        if (isset($data['amount']) && $data['amount'] !== $amount) {
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

    public function listPayments(Request $request)
    {
        return response()->json(
            $request->user()
                ->payments()
                ->orderByDesc('id')
                ->paginate(20)
        );
    }

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

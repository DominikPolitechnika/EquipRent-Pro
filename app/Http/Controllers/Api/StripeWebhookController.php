<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function __construct(protected StripeService $stripeService) {}

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $signature, $secret);
        } catch (UnexpectedValueException|SignatureVerificationException $e) {
            Log::warning('Nieprawidłowy webhook Stripe: '.$e->getMessage());

            return response()->json(['message' => 'Invalid signature'], 400);
        }

        try {
            match ($event->type) {
                'invoice.paid' => $this->onInvoicePaid($event->data->object),
                'invoice.payment_failed' => $this->onInvoicePaymentFailed($event->data->object),
                'invoice.payment_action_required' => $this->onInvoiceActionRequired($event->data->object),
                'charge.refunded' => $this->onChargeRefunded($event->data->object),
                default => null,
            };
        } catch (\Throwable $e) {
            // Zwracamy mimo błędu 200, żeby Stripe nie zasypał nas
            // retry'ami błędu, który i tak wynika z bugu po naszej
            // stronie (retry tego nie naprawi) — ale koniecznie logujemy,
            // bo inaczej zdarzenie (np. udany zwrot) zniknie bez śladu.
            Log::error('Błąd podczas przetwarzania webhooka Stripe', [
                'event_type' => $event->type,
                'event_id' => $event->id,
                'message' => $e->getMessage(),
            ]);
        }

        return response()->json(['received' => true]);
    }

    protected function findPayment($stripeInvoice): ?Payment
    {
        // payments.stripe_payment_intent_id jest naszym jedynym punktem
        // zaczepienia — invoice.payment_intent to ID PaymentIntenta.
        if (! $stripeInvoice->payment_intent) {
            return null;
        }

        return Payment::where('stripe_payment_intent_id', $stripeInvoice->payment_intent)->first();
    }

    protected function onInvoicePaid($stripeInvoice): void
    {
        $payment = $this->findPayment($stripeInvoice);

        if (! $payment) {
            return;
        }

        $payment->update([
            'status' => Payment::STATUS_SUCCEEDED,
            'paid_at' => now(),
        ]);

        // Domyka to samo, co StripeService::charge() robi synchronicznie —
        // ważne dla przypadków, gdy potwierdzenie 3DS przychodzi
        // asynchronicznie (poza naszym stub-em) i status succeeded trafia
        // do nas dopiero przez webhook.
        $this->stripeService->activateReservationIfAwaitingPayment($payment->fresh());
    }

    protected function onInvoicePaymentFailed($stripeInvoice): void
    {
        $this->findPayment($stripeInvoice)?->update([
            'status' => Payment::STATUS_FAILED,
        ]);
    }

    protected function onInvoiceActionRequired($stripeInvoice): void
    {
        $this->findPayment($stripeInvoice)?->update([
            'status' => Payment::STATUS_REQUIRES_ACTION,
        ]);

        // tu warto wysłać e-mail z linkiem hosted_invoice_url, żeby klient
        // ręcznie dokończył autoryzację 3DS przy obciążeniu off-session
    }

    /**
     * Domykający element obsługi zwrotów: gdyby zwrot został wykonany
     * poza naszym API (np. ręcznie z panelu Stripe) albo gdyby jego
     * status zmienił się asynchronicznie (niektóre metody zwrotu nie są
     * natychmiastowe), ten webhook i tak zsynchronizuje lokalny rekord.
     * Idempotentny z natury — wielokrotne wywołanie z tym samym stanem
     * nic nie psuje.
     */
    protected function onChargeRefunded($charge): void
    {
        if (! $charge->payment_intent) {
            return;
        }

        $payment = Payment::where('stripe_payment_intent_id', $charge->payment_intent)->first();

        if (! $payment) {
            return;
        }

        $refundedAmount = $charge->amount_refunded;

        $payment->update([
            'refunded_amount' => $refundedAmount,
            'status' => $refundedAmount >= $payment->totalPrice
                ? Payment::STATUS_REFUNDED
                : Payment::STATUS_PARTIALLY_REFUNDED,
        ]);
    }
}

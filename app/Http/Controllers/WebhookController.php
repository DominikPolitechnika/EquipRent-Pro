<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret')
            );
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $intent = $event->data->object;
                Payment::where('stripe_payment_intent_id', $intent->id)
                    ->update(['status' => 'succeeded']);
                break;

            case 'payment_intent.payment_failed':
                $intent = $event->data->object;
                Payment::where('stripe_payment_intent_id', $intent->id)
                    ->update(['status' => 'failed']);
                break;
        }

        return response('OK', 200);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    protected StripeClient $stripe;

    public function __contruct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function createIntent(Request $request)
    {
        $validated = $request->validate([
            'reservationId' => 'required|integer|exists:reservations,id',
        ]);

        $reservation = $paymentService->findPayableReservation(
            $validated['reservationID'],
            auth()->id()
        );

        #brak walidacji

        $price = $reservation->getTotalPrice();

        $paymentIntent = $this->stripe->paymentIntents->create([
            'amount' => $price * 100, // Stripe liczy w groszach
            'currency' => 'pln',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => [
                'reservationId' => $validated['reservationId'],
                'userId' => auth()->id(),
            ],
        ]);

        Payment::create([
            'userId' => auth()->id(),
            'gatewayId' => 1, //stały ID oznaczający "Stripe"
            'reservationID' => $validated['reservationId'],
            'totalPrice' => $price,
            'status' => 'pending',
            'stripe_payment_intent_id' => $paymentIntent->id,
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }
}

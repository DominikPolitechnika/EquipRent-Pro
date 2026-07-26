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

    #niedługo dokona się tutaj cud stworzenia, proszę o cierpliwość
}

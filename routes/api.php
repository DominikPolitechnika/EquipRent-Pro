<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Tutaj dodajecie wasze kontrolery API
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\StatisticsController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{userID}', [UserController::class, 'getUsersDetails']);
});

Route::middleware('auth:sanctum')->prefix('payments')->group(function () {
    Route::post('/setup-intent', [PaymentController::class, 'createSetupIntent']);
    Route::post('/payment-methods', [PaymentController::class, 'storePaymentMethod']);
    Route::get('/payment-methods', [PaymentController::class, 'listPaymentMethods']);
    Route::delete('/payment-methods/{paymentMethod}', [PaymentController::class, 'destroyPaymentMethod']);
    Route::post('/charge', [PaymentController::class, 'charge']);
    Route::post('/charge-off-session', [PaymentController::class, 'chargeOffSession']);
    Route::get('/', [PaymentController::class, 'listPayments']);
    Route::get('/{payment}/invoice', [PaymentController::class, 'invoice']);
    Route::post('/{payment}/refund', [PaymentController::class, 'refund']);
});

// Webhook Stripe — bez middleware auth/CSRF
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

Route::middleware(['auth:sanctum', 'admin'])->prefix('statistics')->group(function () {
    Route::get('/reservations-count', [StatisticsController::class, 'reservationsCount']);
    Route::get('/monthly-revenue', [StatisticsController::class, 'monthlyRevenue']);
    Route::get('/top-products', [StatisticsController::class, 'topProducts']);
    Route::get('/weekly-income', [StatisticsController::class, 'weeklyIncome']);
    Route::get('/latest-reservations', [StatisticsController::class, 'latestReservations']);
});
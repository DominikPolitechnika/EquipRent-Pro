<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Tutaj dodajecie wasze kontrolery API
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\AdminReservationController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{userID}', [UserController::class, 'getUsersDetails']);
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


Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/reservations', [AdminReservationController::class, 'index']);
    Route::get('/reservations/{id}', [AdminReservationController::class, 'show']);
    Route::patch('/reservations/{id}', [AdminReservationController::class, 'update']);
});





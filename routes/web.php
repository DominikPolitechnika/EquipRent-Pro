<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');

Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register');

Route::get('/api/sample-equipment', function () {
    return response()->json([
        'data' => [
            [
                'id' => 1,
                'name' => 'Wiertarka Bosch GSB 18V-55',
                'category' => 'Elektronarzedzia',
                'daily_rate' => 49.99,
                'status' => 'dostepny',
            ],
            [
                'id' => 2,
                'name' => 'Zageszczarka 85 kg',
                'category' => 'Budowlane',
                'daily_rate' => 129.00,
                'status' => 'wypozyczony',
            ],
            [
                'id' => 3,
                'name' => 'Drabina aluminiowa 3x9',
                'category' => 'Rusztowania i drabiny',
                'daily_rate' => 39.50,
                'status' => 'serwis',
            ],
        ],
        'meta' => [
            'currency' => 'PLN',
            'generated_at' => now()->toIso8601String(),
        ],
    ]);
});

Route::view('/demo-layout', 'pages.demo-layout');

require __DIR__.'/auth.php';

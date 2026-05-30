<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;

// Strona główna - przekierowanie do logowania
Route::get('/', function () {
    return redirect()->route('login');
});

// Trasy autoryzacji (logowanie, rejestracja)
Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');

Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register');

Route::view('/rezerwacje', 'rezerwacje')
    ->middleware('auth')
    ->name('rezerwacje');

// Profil użytkownika
Route::view('/profil', 'profil')
    ->middleware('auth')
    ->name('profil');

Route::view('/profil_edytuj', 'profil_edytuj')
    ->middleware('auth')
    ->name('profil_edytuj');

// Zapis edycji profilu (zaślepka - na razie przekierowuje z powrotem na profil)
Route::put('/profil', function () {
    // TODO: implementacja zapisu danych użytkownika
    return redirect()->route('profil')->with('success', 'Profil został zaktualizowany.');
})->middleware('auth')->name('profil.update'); 

// Trasy dostępne tylko dla zalogowanych użytkowników
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
    Route::get('/produkt/{id}', [ProductController::class, 'index'])->name('product');
    Route::view('/demo-layout', 'pages.demo-layout');
});

// API - dostępne tylko dla zalogowanych
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/sample-equipment', function () {
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
});

Route::get('/forgot-password', function() {
    return view('auth.forgot-password');
});

Route::get('/reset-password', function () {
    return redirect('/forgot-password');
});

require __DIR__.'/auth.php';
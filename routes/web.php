<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\OpinionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\ProfileController;

// Strona główna - przekierowanie w zależności od roli / do logowania
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->isAdmin() ? 'dashboard' : 'catalog');
    }
    return redirect()->route('login');
});

// Trasy autoryzacji
Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');
// ===== Strony informacyjne (dostępne dla wszystkich) =====
Route::view('/o-nas',                 'o_nas')->name('o_nas');
Route::view('/regulamin',             'regulamin')->name('regulamin');
Route::view('/polityka-prywatnosci',  'polityka_prywatnosci')->name('polityka');

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

// Zapis edycji profilu
Route::put('/profil', [ProfileController::class, 'update'])
    ->middleware('auth')
    ->name('profil.update');

// Trasy dostępne dla wszystkich zalogowanych użytkowników (klient + admin)
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/catalog', [ProductController::class, 'show'])->name('catalog');

    // alias po polsku, gdyby frontend gdzieś linkował do /katalog
    Route::get('/katalog', [ProductController::class, 'show'])->name('katalog');

    Route::get('/produkt/{id}', [ProductController::class, 'index'])->name('product');

    Route::view('/demo-layout', 'pages.demo-layout');
    Route::view('/platnosc', 'platnosc')->name('platnosc');
});

// Trasy panelu administracyjnego - tylko dla roli admina
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/produkt/{id}/edytuj', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/produkt/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::patch('/produkt/{id}/status', [ProductController::class, 'toggleAvailability'])->name('product.status');
    Route::delete('/produkt/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::get('/produkt/{id}/naprawy', [ProductController::class, 'repairs'])->name('product.repairs');
    Route::post('/produkt/{id}/naprawy', [ProductController::class, 'storeRepair'])->name('product.repairs.store');
    Route::delete('/produkt/{id}/naprawy/{repairId}', [ProductController::class, 'deleteRepair'])->name('product.repairs.destroy');
    Route::get('/produkt/{id}/rezerwacje', [ProductController::class, 'reservations'])->name('product.reservations');
    Route::get('/inwentarz', [ProductController::class, 'inventory'])->name('equipment.list');
    Route::view('/lista-uzytkownikow', 'list_users')->name('users.list');
    Route::view('/uzytkownik-szczegoly/{id}', 'user_details')->name('users.show');
    Route::view('/rejestr-wypozyczen', 'list_rentals')->name('rentals.list');
    Route::view('/panel-glowny', 'dashboard')->name('dashboard');
});

// API - dostępne tylko dla zalogowanych
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/user/profile', [UserController::class, 'me']);

    Route::get('/reservations/my', [ReservationController::class, 'my']);
    Route::get('/reservations/active', [ReservationController::class, 'active']);
    Route::get('/reservations/completed', [ReservationController::class, 'completed']);
    Route::get('/products/{productId}/reservations/my', [ReservationController::class, 'myForProduct']);
    Route::patch('/reservations/{reservationId}/cancel', [ReservationController::class, 'cancel']);
    Route::post('/products/{productId}/reservations', [ReservationController::class, 'store']);
    Route::get('/products/{productId}/opinions', [OpinionController::class, 'index']);
    Route::post('/products/{productId}/opinions', [OpinionController::class, 'store']);
    Route::get('/products/{productId}/opinions/summary', [OpinionController::class, 'summary']);
    Route::get('/products/{productId}/opinions/can-review', [OpinionController::class, 'canReview']);
    Route::patch('/opinions/{opinionId}', [OpinionController::class, 'update']);
    Route::delete('/opinions/{opinionId}', [OpinionController::class, 'destroy']);
    Route::get('/reservations/summary/income', [ReservationController::class, 'incomeSummary']);
    Route::get('/reservations/summary/count', [ReservationController::class, 'countSummary']);
    Route::get('/reservations/upcoming', [ReservationController::class, 'upcoming']);
    Route::get('/products/{productId}/reservations/booked-dates', [ReservationController::class, 'bookedDates']);

    //trasa wykorzystywana przez system płatności
    Route::get('/reservations/{reservationId}', [ReservationController::class, 'show'])
        ->whereNumber('reservationId');

    Route::get('/alerts', [AlertController::class, 'index']);
    Route::patch('/alerts/{alertId}/read', [AlertController::class, 'markRead']);

    // ===================== PŁATNOŚCI (Stripe) =====================
    Route::prefix('payments')->group(function () {
        Route::post('/setup-intent', [PaymentController::class, 'createSetupIntent']);
        Route::post('/payment-methods', [PaymentController::class, 'storePaymentMethod']);
        Route::get('/payment-methods', [PaymentController::class, 'listPaymentMethods']);
        Route::delete('/payment-methods/{paymentMethod}', [PaymentController::class, 'destroyPaymentMethod']);

        Route::post('/charge', [PaymentController::class, 'charge']);
        Route::post('/charge-off-session', [PaymentController::class, 'chargeOffSession']);
        Route::post('/{payment}/confirm-3ds-stub', [PaymentController::class, 'confirmThreeDsStub']);

        Route::get('/', [PaymentController::class, 'listPayments']);
        Route::get('/penalties', [PaymentController::class, 'listPenalties']);
        Route::get('/{payment}/invoice', [PaymentController::class, 'invoice']);
        Route::post('/{payment}/refund', [PaymentController::class, 'refund']);
    });

    Route::middleware(['admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{userID}', [UserController::class, 'getUsersDetails']);
        Route::patch('/users/{userID}', [UserController::class, 'update']);
        Route::patch('/users/{userID}/toggle-block', [UserController::class, 'toggleBlock']);
    });
});

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
});

Route::get('/reset-password', function () {
    return view('auth.reset-password');
});

require __DIR__.'/auth.php';

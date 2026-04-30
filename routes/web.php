<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\ProductController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [KatalogController::class, 'index'])->name('Katalog');
Route::get('/produkt/{id}', [ProductController::class, 'showPage'])->name('product');

// Route::get('/api/products/{id}', ...); // <- zakomentowane API

require __DIR__.'/auth.php';

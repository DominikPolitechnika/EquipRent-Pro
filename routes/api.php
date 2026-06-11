<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Tutaj dodajecie wasze kontrolery API
use App\Http\Controllers\Api\UserController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{userID}', [UserController::class, 'getUsersDetails']);
});
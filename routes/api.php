<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(JwtMiddleware::class)->group(function () {
    Route::middleware(['role:customer'])->group(function () {
        Route::post('/transactions', [TransactionController::class, 'store']);
    });
    Route::controller(TransactionController::class)->group(function () {
        Route::get('/transactions', 'getByUser');
    });

    Route::controller(AccountController::class)->group(function () {
        Route::get('/account', 'details');
    });
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

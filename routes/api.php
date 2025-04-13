<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CepController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\UserHasAdminRoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(JwtMiddleware::class)->group(function () {

//    Route::middleware(UserHasAdminRoleMiddleware::class)->group(function () {
//        Route::controller(ItemController::class)->group(function () {
//            Route::post('/items', 'store');
//            Route::patch('/items/{id}', 'update');
//            Route::delete('/items/{id}', 'destroy');
//        });
//
//        Route::controller(ItemTypeController::class)->group(function () {
//            Route::post('/item_types', 'store');
//            Route::patch('/item_types/{id}', 'update');
//            Route::delete('/item_types/{id}', 'destroy');
//        });
//
//    });
    Route::controller(TransactionController::class)->group(function () {
        Route::post('/transactions', 'store');
        Route::get('/transactions', 'getByUser');
    });
    Route::controller(AccountController::class)->group(function () {
        Route::get('/account', 'details');
    });
});

//Route::controller(ItemController::class)->group(function () {
//    Route::get('/items', 'get');
//    Route::get('/items/{id}', 'details');
//});
//
//Route::controller(ItemTypeController::class)->group(function () {
//    Route::get('/item_types', 'get');
//    Route::get('/item_types/{id}', 'details');
//});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});
//
//Route::controller(CepController::class)->group(function () {
//    Route::get('/cep/search/{cep}', 'searchCep');
//});

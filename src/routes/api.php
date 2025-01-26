<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ProductController;

// public routes
Route::get('/users/{user}', [UserController::class, 'show'])
    ->name('user.show');
Route::post('/register', [UserController::class, 'register'])
    ->name('user.register');
Route::post('/login', [LoginController::class, 'login'])
    ->name('user.login');

// protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/products', ProductController::class);
    Route::get('products/{product}/stock', [StockController::class, 'show']);
    Route::put('products/{product}/stock', [StockController::class, 'update']);
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('user.logout');
});

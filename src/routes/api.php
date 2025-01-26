<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// public routes
Route::post('/register', [UserController::class, 'register'])
    ->name('user.register');
Route::post('/login', [LoginController::class, 'login'])
    ->name('user.login');

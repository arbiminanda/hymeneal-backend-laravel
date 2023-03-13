<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * route "/register"
 * @method "POST"
 */
Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');

/**
 * route "/login"
 * @method "POST"
 */
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');

/**
 * route "/user"
 * @method "GET"
 */
Route::middleware('auth:api')->get('/user', App\Http\Controllers\Api\UserController::class)->name('user');

/**
 * route group "/confirmation"
 * @method "GET, POST, PUT, DELETE"
 */
Route::prefix('/confirmation')->group(function () {
    Route::middleware('auth:api')->get('', [App\Http\Controllers\Api\ConfirmationController::class,'index']);
    Route::post('', [App\Http\Controllers\Api\ConfirmationController::class,'create']);
});

/**
 * route "/greetings"
 * @method "GET"
 */
Route::get('/greetings', [App\Http\Controllers\Api\ConfirmationController::class,'greetings'])->name('greetings');
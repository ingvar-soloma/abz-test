<?php

use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::resource('/users', UserController::class)
        ->only(['show', 'index']);

    Route::middleware('token')->group(function () {
        Route::post('/users', [UserController::class, 'store']);
    });

    Route::get('/token', TokenController::class);
});


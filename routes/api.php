<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\AvailabilityController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::get('/api/documentation', function () {
    return redirect('/api/documentation/index.html');
});

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/properties', [PropertyController::class, 'index']);
    Route::get('/properties/{id}', [PropertyController::class, 'show']);
    Route::post('/bookings', [BookingController::class, 'store']);

    Route::middleware('role:admin')->group(function () {

        Route::apiResource('properties', PropertyController::class)->except(['index', 'show']);

        Route::get('/bookings', [BookingController::class, 'index']);

        Route::post('/availability', [AvailabilityController::class, 'store']);
    });
});

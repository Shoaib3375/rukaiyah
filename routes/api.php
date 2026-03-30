<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\RaqiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);
        Route::middleware('auth:api')->group(function () {
            Route::post('logout',  [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('me',       [AuthController::class, 'me']);
        });
    });

    // Patient
    Route::middleware(['auth:api', 'role:patient'])->prefix('patient')->group(function () {
        Route::get ('appointments',                      [AppointmentController::class, 'patientIndex']);
        Route::post('appointments',                      [AppointmentController::class, 'store']);
        Route::get ('appointments/{appointment}',        [AppointmentController::class, 'show']);
        Route::delete('appointments/{appointment}',      [AppointmentController::class, 'cancel']);
        Route::get ('raqis',                             [RaqiController::class, 'index']);
        Route::get ('raqis/{raqi}',                      [RaqiController::class, 'show']);
    });

    // Raqi
    Route::middleware(['auth:api', 'role:raqi', 'raqi.approved'])->prefix('raqi')->group(function () {
        Route::get ('appointments',                      [AppointmentController::class, 'raqiIndex']);
    });
});

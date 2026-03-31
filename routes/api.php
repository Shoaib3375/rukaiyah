<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\RaqiController;
use App\Http\Controllers\Api\PatientProfileController;
use App\Http\Controllers\Api\RaqiProfileController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\SessionParticipantController;
use App\Http\Controllers\Api\SessionNoteController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\FollowUpController;
use App\Http\Controllers\Api\InviteController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\AdminRaqiController;
use App\Http\Controllers\Api\Admin\AdminAppointmentController;
use App\Http\Controllers\Api\Admin\AdminStatsController;
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
        Route::get ('profile',                           [PatientProfileController::class, 'show']);
        Route::put ('profile',                           [PatientProfileController::class, 'update']);
        Route::get ('appointments',                      [AppointmentController::class, 'patientIndex']);
        Route::post('appointments',                      [AppointmentController::class, 'store']);
        Route::get ('appointments/{appointment}',        [AppointmentController::class, 'show']);
        Route::delete('appointments/{appointment}',      [AppointmentController::class, 'cancel']);
        Route::get ('raqis',                             [RaqiController::class, 'index']);
        Route::get ('raqis/{raqi}',                      [RaqiController::class, 'show']);
        Route::get ('raqis/{raqi}/available-slots',      [RaqiController::class, 'availableSlots']);
        Route::post('reviews',                           [ReviewController::class, 'store']);
        Route::get ('notifications',                     [NotificationController::class, 'index']);
        Route::put ('notifications/{notification}/read', [NotificationController::class, 'markRead']);
    });

    // Raqi
    Route::middleware(['auth:api', 'role:raqi', 'raqi.approved'])->prefix('raqi')->group(function () {
        Route::get('profile',  [RaqiProfileController::class, 'show']);
        Route::put('profile',  [RaqiProfileController::class, 'update']);
        Route::apiResource('availability', AvailabilityController::class);
        Route::get ('appointments',                               [AppointmentController::class, 'raqiIndex']);
        Route::put ('appointments/{appointment}/accept',          [AppointmentController::class, 'accept']);
        Route::put ('appointments/{appointment}/decline',         [AppointmentController::class, 'decline']);
        Route::put ('appointments/{appointment}/complete',        [AppointmentController::class, 'complete']);
        Route::post('appointments/{appointment}/invite',          [SessionParticipantController::class, 'invite']);
        Route::get ('appointments/{appointment}/participants',    [SessionParticipantController::class, 'index']);
        Route::get ('appointments/{appointment}/notes',           [SessionNoteController::class, 'index']);
        Route::post('appointments/{appointment}/notes',           [SessionNoteController::class, 'store']);
        Route::post('appointments/{appointment}/followup',        [FollowUpController::class, 'store']);
        Route::get ('notifications',                              [NotificationController::class, 'index']);
        Route::put ('notifications/{notification}/read',          [NotificationController::class, 'markRead']);
        Route::get ('raqis',                                      [RaqiController::class, 'index']);
    });

    // Admin
    Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('users',                        [AdminUserController::class, 'index']);
        Route::get('users/{user}',                 [AdminUserController::class, 'show']);
        Route::put('users/{user}/activate',        [AdminUserController::class, 'activate']);
        Route::get('raqis/pending',                [AdminRaqiController::class, 'pending']);
        Route::get('raqis/approved',               [AdminRaqiController::class, 'approved']);
        Route::put('raqis/{raqi}/approve',         [AdminRaqiController::class, 'approve']);
        Route::put('raqis/{raqi}/suspend',         [AdminRaqiController::class, 'suspend']);
        Route::get('appointments',                 [AdminAppointmentController::class, 'index']);
        Route::put('appointments/{appointment}',   [AdminAppointmentController::class, 'update']);
        Route::get('stats',                        [AdminStatsController::class, 'index']);
    });

    // Invite token flow
    Route::middleware('auth:api')->group(function () {
        Route::post('invites/{token}/accept',  [InviteController::class, 'accept']);
        Route::post('invites/{token}/decline', [InviteController::class, 'decline']);
    });
});

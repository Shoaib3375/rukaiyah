<?php

use Illuminate\Support\Facades\Route;

// Serve Vue SPA on all non-API routes
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

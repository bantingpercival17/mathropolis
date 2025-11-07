<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
// SPA catch-all route
// ✅ Google login routes first
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// ✅ Catch-all for Vue routes LAST
Route::view('/{any}', 'vue')->where('any', '^(?!auth).*');

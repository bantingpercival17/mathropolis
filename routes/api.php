<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use Illuminate\Routing\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/progress', [GameController::class, 'getProgress']);
    Route::post('/progress', [GameController::class, 'saveProgress']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

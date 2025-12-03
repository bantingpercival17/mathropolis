<?php

use App\Http\Controllers\GameProgressController;
use App\Models\GameProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    // Student Routes
    Route::prefix('/student')->controller(GameProgressController::class)->group(function () {
        Route::post('save-progress', 'updateProgress');
        Route::get('progress', 'gameProgress');
        Route::post('update-progress', 'updateProgress');
    });
    Route::prefix('/teacher')->group(function () {
        Route::get('/retrieve-student', [GameProgressController::class, 'studentsProgress']);
    });
});

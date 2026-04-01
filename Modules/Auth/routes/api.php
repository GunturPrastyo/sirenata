<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\Api\AuthController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    // Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    // Route::get('/reset-password/{token}', function (string $token) {
    //     return response()->json(['message' => 'Please submit a POST request to /api/v1/reset-password with this token, email, password, and password_confirmation.', 'token' => $token]);
    // })->name('password.reset');
    // Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);  

    Route::get('/users', [AuthController::class, 'index']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

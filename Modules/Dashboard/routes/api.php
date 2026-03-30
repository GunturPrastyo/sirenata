<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\Api\UserDashboardController;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/user/profile', [UserDashboardController::class, 'profile']);
    Route::patch('/user/instansi', [UserDashboardController::class, 'updateInstansi']);
});
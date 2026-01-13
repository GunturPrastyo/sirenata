<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\ActivityController;
use Modules\User\Http\Controllers\UserController;

// Route::middleware(['auth', 'verified'])->group(function () {});
Route::resource('users', UserController::class)->names('user');

Route::get('/activity', [ActivityController::class, 'index'])->name('activity');
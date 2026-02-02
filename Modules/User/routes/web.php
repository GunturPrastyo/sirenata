<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\ActivityController;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Controllers\UserManagementController;

// Route::middleware(['auth', 'verified'])->group(function () {});
Route::resource('users', UserController::class)->names('user');

Route::get('/activity', [ActivityController::class, 'index'])->name('activity');

Route::prefix('super-admin')->middleware(['auth', 'role:super-admin'])->name('super-admin.')->group(function () {
    Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management');
    Route::get('/user-management/{user:id}/edit',[UserManagementController::class, 'edit'])->name('user-management.edit');
});
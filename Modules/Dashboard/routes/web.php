<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\PortalDashboardController;
use Modules\Dashboard\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;

Route::get('/portal-dashboard', [PortalDashboardController::class, 'index'])->middleware(['auth'])->name('portal-dashboard');

Route::prefix('super-admin')->middleware(['auth', 'role:super-admin'])->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\PortalDashboardController;
use Modules\Dashboard\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use Modules\Dashboard\Http\Controllers\AdminPusat\DashbordController as AdminPusatDashboardController;
use Modules\Dashboard\Http\Controllers\AdminProvinsi\DashboardController as AdminProvinsiDashboardController;
use Modules\Dashboard\Http\Controllers\AdminKabKota\DashboardController as AdminKabKotaDashboardController;

Route::get('/portal-dashboard', [PortalDashboardController::class, 'index'])->middleware(['auth'])->name('portal-dashboard');

Route::prefix('super-admin')->middleware(['auth', 'role:super-admin'])->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('admin-pusat')->middleware(['auth', 'role:admin-pusat'])->name('admin-pusat.')->group(function () {
    Route::get('/dashboard', [AdminPusatDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('admin-provinsi')->middleware(['auth', 'role:admin-province'])->name('admin-province.')->group(function () {
    Route::get('/dashboard', [AdminProvinsiDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('admin-kab-kota')->middleware(['auth', 'role:admin-kab-kota'])->name('admin-kab-kota.')->group(function () {
    Route::get('/dashboard', [AdminKabKotaDashboardController::class, 'index'])->name('dashboard');
});

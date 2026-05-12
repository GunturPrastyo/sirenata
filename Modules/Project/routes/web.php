<?php

use Illuminate\Support\Facades\Route;
use Modules\Project\Http\Controllers\AdminPusat\ProjectController as AdminPusatProjectController;
use Modules\Project\Http\Controllers\AdminProvince\ProjectController as AdminProvinceProjectController;
use Modules\Project\Http\Controllers\AdminKabKota\ProjectController as AdminKabKotaProjectController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Pusat
    Route::prefix('admin-pusat')->middleware(['auth', 'role:admin-pusat|super-admin'])->name('admin-pusat.')->group(function () {
        Route::resource('projects', AdminPusatProjectController::class)->names('project');
    });

    // Admin Provinsi
    Route::prefix('admin-province')->middleware(['auth', 'role:admin-province'])->name('admin-province.')->group(function () {
        Route::resource('projects', AdminProvinceProjectController::class)->names('project');
    });

    // Admin Kab / Kota
    Route::prefix('admin-kab-kota')->middleware(['auth', 'role:admin-kab-kota'])->name('admin-kab-kota.')->group(function () {
        Route::resource('projects', AdminKabKotaProjectController::class)->names('project');
    });
});

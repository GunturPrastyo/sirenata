<?php

use Illuminate\Support\Facades\Route;
use Modules\LMS\Http\Controllers\AdminProvince\RekapitulasiController as AdminProvinceRekapitulasiController;
use Modules\LMS\Http\Controllers\AdminPusat\RekapitulasiController;

Route::prefix('admin-pusat')->middleware(['auth', 'role:admin-pusat'])->name('admin-pusat.')->group(function () {
    Route::prefix('rekapitulasi')->name('rekapitulasi.')->controller(RekapitulasiController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/kab-kota/{provinceCode}', 'kabKota')->name('kab-kota');
    });
});

Route::prefix('admin-province')->middleware(['auth', 'role:admin-province'])->name('admin-province.')->group(function () {
    Route::prefix('rekapitulasi')->name('rekapitulasi.')->controller(AdminProvinceRekapitulasiController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });
});
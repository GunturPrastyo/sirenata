<?php

use Illuminate\Support\Facades\Route;
use Modules\LMS\Http\Controllers\AdminPusat\RekapitulasiController;

Route::prefix('admin-pusat')->middleware(['auth', 'role:admin-pusat'])->name('admin-pusat.')->group(function () {
    Route::prefix('rekapitulasi')->name('rekapitulasi.')->controller(RekapitulasiController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });
});
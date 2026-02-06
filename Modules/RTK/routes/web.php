<?php

use Illuminate\Support\Facades\Route;
use Modules\RTK\Http\Controllers\AdminPusat\RencanaTenagaKerjaDaerahController;
use Modules\RTK\Http\Controllers\RencanaTenagaKerjaNasionalController;
use Modules\RTK\Http\Controllers\AdminProvinsi\RencanaTenagaKerjaProvinceController;

Route::prefix('admin-pusat')->middleware(['auth', 'role:admin-pusat'])->name('admin-pusat.')->group(function () {
    Route::resource('rencana-tenaga-kerja-nasional', RencanaTenagaKerjaNasionalController::class)->names('rtkn');

    Route::prefix('rencana-tenaga-kerja-daerah')->name('rtkd.')->group(function () {
        Route::get('/', [RencanaTenagaKerjaDaerahController::class, 'index'])->name('index');
    });
});


Route::prefix('admin-province')->middleware(['auth', 'role:admin-province'])->name('admin-province.')->group(function () {
    Route::resource('rencana-tenaga-kerja-daerah-provinsi', RencanaTenagaKerjaProvinceController::class)
        ->parameters(['rencana-tenaga-kerja-daerah-provinsi' => 'rtkdp',])
        ->names('rtkdp');
});

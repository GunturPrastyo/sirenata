<?php

use Illuminate\Support\Facades\Route;
use Modules\RTK\Http\Controllers\AdminProvinsi\RencanaTenagaKerjaKabKotaController;
use Modules\RTK\Http\Controllers\AdminPusat\RencanaTenagaKerjaDaerahController;
use Modules\RTK\Http\Controllers\RencanaTenagaKerjaNasionalController;
use Modules\RTK\Http\Controllers\AdminProvinsi\RencanaTenagaKerjaProvinceController;
use Modules\RTK\Http\Controllers\AdminKabKota\RencanaTenagaKerjaKabKotaController as RencanaTenagaKerjaKabKotaControllerAdminKabKota;
use Modules\RTK\Http\Controllers\AdminKabKota\RtkKabKotaDashboardController;

Route::prefix('admin-pusat')->middleware(['auth', 'role:admin-pusat'])->name('admin-pusat.')->group(function () {
    Route::resource('rencana-tenaga-kerja-nasional', RencanaTenagaKerjaNasionalController::class)->names('rtkn');

    Route::prefix('rencana-tenaga-kerja-daerah')->name('rtkd.')->group(function () {
        Route::get('/', [RencanaTenagaKerjaDaerahController::class, 'index'])->name('index');
        Route::get('/{provinceCode}/kab-kota', [RencanaTenagaKerjaDaerahController::class, 'kabKota'])->name('kab-kota');
    });
});


Route::prefix('admin-province')->middleware(['auth', 'role:admin-province'])->name('admin-province.')->group(function () {
    Route::resource('rencana-tenaga-kerja-daerah-provinsi', RencanaTenagaKerjaProvinceController::class)
        ->parameters(['rencana-tenaga-kerja-daerah-provinsi' => 'rtkdp',])
        ->names('rtkdp');

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [RencanaTenagaKerjaKabKotaController::class, 'index'])->name('index');
    });
});

Route::prefix('admin-kab-kota')->middleware(['auth', 'role:admin-kab-kota'])->name('admin-kab-kota.')->group(function () {
    Route::resource('rencana-tenaga-kerja-daerah-kab-kota', RencanaTenagaKerjaKabKotaControllerAdminKabKota::class)
        ->parameters(['rencana-tenaga-kerja-daerah-kab-kota' => 'rtkd',])
        ->names('rtkd');

    Route::prefix('laporan')->name('laporan.')->controller(RtkKabKotaDashboardController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });
});



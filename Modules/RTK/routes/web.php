<?php

use Illuminate\Support\Facades\Route;
use Modules\RTK\Http\Controllers\AdminProvinsi\RencanaTenagaKerjaKabKotaController;
use Modules\RTK\Http\Controllers\AdminPusat\RencanaTenagaKerjaDaerahController;
use Modules\RTK\Http\Controllers\RencanaTenagaKerjaNasionalController;
use Modules\RTK\Http\Controllers\AdminProvinsi\RencanaTenagaKerjaProvinceController;
use Modules\RTK\Http\Controllers\AdminKabKota\RencanaTenagaKerjaKabKotaController as RencanaTenagaKerjaKabKotaControllerAdminKabKota;
use Modules\RTK\Http\Controllers\AdminKabKota\RtkKabKotaDashboardController;
use Modules\RTK\Http\Controllers\AdminPusat\RTKApprovalPusatController;

Route::prefix('admin-pusat')->middleware(['auth', 'role:admin-pusat'])->name('admin-pusat.')->group(function () {
    Route::resource('rencana-tenaga-kerja-nasional', RencanaTenagaKerjaNasionalController::class)->names('rtkn');

    Route::prefix('rencana-tenaga-kerja-daerah')->name('rtkd.')->group(function () {
        Route::get('/', [RencanaTenagaKerjaDaerahController::class, 'index'])->name('index');
        Route::get('/{provinceCode}/kab-kota', [RencanaTenagaKerjaDaerahController::class, 'kabKota'])->name('kab-kota');
        
        Route::get('/province/{provinceCode}/show', [RencanaTenagaKerjaDaerahController::class, 'showProvince'])->name('show-province');

        Route::get('/regency/{regencyCode}/show', [RencanaTenagaKerjaDaerahController::class, 'showRegency'])->name('show-regency');
        
        Route::post('/province/{rtk}/approve', [RTKApprovalPusatController::class, 'approveProvince'])->name('approveProvince');
        Route::post('/regency/{rtk}/approve', [RTKApprovalPusatController::class, 'approveKabKota'])->name('approveKabKota');
        // Route::post('/{rtk}/reject', [RTKApprovalPusatController::class, 'rejectProvince'])->name('reject');
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



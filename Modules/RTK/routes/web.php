<?php

use Illuminate\Support\Facades\Route;
use Modules\RTK\Http\Controllers\AdminKabKota\ExportRtkRegencyController;
use Modules\RTK\Http\Controllers\AdminProvinsi\RencanaTenagaKerjaKabKotaController;
use Modules\RTK\Http\Controllers\AdminPusat\RencanaTenagaKerjaDaerahController;
use Modules\RTK\Http\Controllers\AdminPusat\RtkNasional\RencanaTenagaKerjaNasionalController;
use Modules\RTK\Http\Controllers\AdminProvinsi\RencanaTenagaKerjaProvinceController;
use Modules\RTK\Http\Controllers\AdminKabKota\RencanaTenagaKerjaKabKotaController as RencanaTenagaKerjaKabKotaControllerAdminKabKota;
use Modules\RTK\Http\Controllers\AdminKabKota\RtkKabKotaDashboardController;
use Modules\RTK\Http\Controllers\AdminProvinsi\ExportRtkProvinceController;
use Modules\RTK\Http\Controllers\AdminProvinsi\RTKApprovalProvinceController;
use Modules\RTK\Http\Controllers\AdminPusat\RTKApprovalPusatController;
use Modules\RTK\Http\Controllers\AdminPusat\RtkSurveyPeriodController;
use Modules\RTK\Http\Controllers\AdminPusat\HasilPemanfaatanRtkdController;
use Modules\RTK\Http\Controllers\AdminProvinsi\PemanfaatanRtkdController;
use Modules\RTK\Http\Controllers\AdminPusat\RtkNasional\ExportRtknController;
use Modules\RTK\Http\Controllers\AdminPusat\RtkNasional\RTKNApprovalController;

Route::prefix('admin-pusat')->middleware(['auth', 'role:admin-pusat'])->name('admin-pusat.')->group(function () {
    Route::resource('rencana-tenaga-kerja-nasional', RencanaTenagaKerjaNasionalController::class)->names('rtkn');

    Route::controller(RTKNApprovalController::class)->name('rtkn.')->group(function () {
        Route::post('{rtkn}/approve-verification', 'approveVerification')->name('approve-verification');
        Route::post('{rtkn}/approve-document', 'approveDocument')->name('approve-document');
        Route::post('{rtkn}/reject', 'rejectRtkn')->name('reject-rtkn');
    });

    Route::controller(ExportRtknController::class)->name('rtkn.')->group(function () {
        Route::get('export', 'ExportRtkn')->name('export');
    });

    Route::resource('survey-periods', RtkSurveyPeriodController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::patch('survey-periods/{survey_period}/activate', [RtkSurveyPeriodController::class, 'activate'])->name('survey-periods.activate');
    Route::patch('survey-periods/{survey_period}/close', [RtkSurveyPeriodController::class, 'close'])->name('survey-periods.close');
    Route::post('survey-periods/{survey_period}/copy-submissions', [RtkSurveyPeriodController::class, 'copySubmissions'])->name('survey-periods.copy-submissions');

    Route::get('hasil-pemanfaatan-rtkd', [HasilPemanfaatanRtkdController::class, 'index'])->name('hasil-pemanfaatan-rtkd.index');
    Route::get('hasil-pemanfaatan-rtkd/export', [HasilPemanfaatanRtkdController::class, 'export'])->name('hasil-pemanfaatan-rtkd.export');
    Route::get('hasil-pemanfaatan-rtkd/{id}', [HasilPemanfaatanRtkdController::class, 'show'])->name('hasil-pemanfaatan-rtkd.show');
    Route::patch('hasil-pemanfaatan-rtkd/{id}/verify', [HasilPemanfaatanRtkdController::class, 'verify'])->name('hasil-pemanfaatan-rtkd.verify');
    Route::get('hasil-pemanfaatan-rtkd/{id}/ubah-sendiri', [HasilPemanfaatanRtkdController::class, 'editOnBehalf'])->name('hasil-pemanfaatan-rtkd.edit-on-behalf');
    Route::post('hasil-pemanfaatan-rtkd/{id}/ubah-sendiri', [HasilPemanfaatanRtkdController::class, 'storeOnBehalf'])->name('hasil-pemanfaatan-rtkd.store-on-behalf');

    Route::prefix('rencana-tenaga-kerja-daerah')->name('rtkd.')->group(function () {
        Route::get('/', [RencanaTenagaKerjaDaerahController::class, 'index'])->name('index');
        Route::get('/export-all-province', [RencanaTenagaKerjaDaerahController::class, 'exportAllProvince'])->name('export-all-province');
        Route::get('/{provinceCode}/kab-kota', [RencanaTenagaKerjaDaerahController::class, 'kabKota'])->name('kab-kota');
        Route::get('/{provinceCode}/export-regency-by-province', [RencanaTenagaKerjaDaerahController::class, 'exportRegencyByProvince'])->name('export-regency-by-province');

        Route::get('/province/{provinceCode}/show', [RencanaTenagaKerjaDaerahController::class, 'showProvince'])->name('show-province');

        Route::get('/province/{provinceCode}/rtk/{rtkdp}/edit', [RencanaTenagaKerjaDaerahController::class, 'editProvince'])
            ->name('edit-province');

        Route::get('/regency/{regencyCode}/rtk/{rtkdp}/edit', [RencanaTenagaKerjaDaerahController::class, 'editRegency'])
            ->name('edit-regency');

        Route::put('/province/{provinceCode}/rtk/{rtkdp}', [RencanaTenagaKerjaDaerahController::class, 'updateProvince'])
            ->name('update-province');
        Route::put('/regency/{regencyCode}/rtk/{rtkdp}', [RencanaTenagaKerjaDaerahController::class, 'updateRegency'])
            ->name('update-regency');
        Route::get('/province/{provinceCode}/export', [RencanaTenagaKerjaDaerahController::class, 'ExportRtkProvince'])->name('show-province-export');

        Route::get('/regency/{regencyCode}/show', [RencanaTenagaKerjaDaerahController::class, 'showRegency'])->name('show-regency');
        Route::get('/regency/{regencyCode}/export', [RencanaTenagaKerjaDaerahController::class, 'ExportRtkRegency'])->name('show-regency-export');

        Route::post('/province/{rtk}/approve-verification', [RTKApprovalPusatController::class, 'approveVerificationProvince'])->name('approveVerificationProvince');
        Route::post('/province/{rtk}/approve-document', [RTKApprovalPusatController::class, 'approveDocumentProvince'])->name('approveDocumentProvince');

        Route::post('/regency/{rtk}/approve-verification', [RTKApprovalPusatController::class, 'approveVerificationKabKota'])->name('approveVerificationKabKota');
        Route::post('/regency/{rtk}/approve-document', [RTKApprovalPusatController::class, 'approveDocumentKabKota'])->name('approveDocumentKabKota');

        Route::post('/province/{rtk}/reject', [RTKApprovalPusatController::class, 'rejectProvince'])->name('rejectProvince');
        Route::post('/regency/{rtk}/reject', [RTKApprovalPusatController::class, 'rejectKabKota'])->name('rejectKabKota');
    });
});

Route::prefix('admin-province')->middleware(['auth', 'role:admin-province'])->name('admin-province.')->group(function () {
    Route::resource('rencana-tenaga-kerja-daerah-provinsi', RencanaTenagaKerjaProvinceController::class)
        ->parameters(['rencana-tenaga-kerja-daerah-provinsi' => 'rtkdp',])
        ->names('rtkdp');

    Route::get('export-rtk-province', [ExportRtkProvinceController::class, 'ExportRtkProvince'])->name('rtkdp-export');

    Route::resource('pemanfaatan-rtkd', PemanfaatanRtkdController::class)->only(['index', 'create', 'store', 'edit', 'update']);

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [RencanaTenagaKerjaKabKotaController::class, 'index'])->name('index');
        Route::get('/export-regency-by-province', [RencanaTenagaKerjaKabKotaController::class, 'exportRegencyByProvince'])->name('export-regency-by-province');
        Route::get('/regency/{regencyCode}/show', [RencanaTenagaKerjaKabKotaController::class, 'showRegency'])->name('show-regency');
        Route::get('/regency/{regencyCode}/export', [RencanaTenagaKerjaKabKotaController::class, 'ExportRtkRegency'])->name('export-regency');

        Route::get('/regency/{regencyCode}/rtk/{rtkdp}/edit', [RencanaTenagaKerjaKabKotaController::class, 'editRegency'])
            ->name('edit-regency');
        Route::put('/regency/{regencyCode}/rtk/{rtkdp}', [RencanaTenagaKerjaKabKotaController::class, 'updateRegency'])
            ->name('update-regency');

        Route::post('/regency/{rtk}/approve-verification', [RTKApprovalProvinceController::class, 'approveVerificationKabKota'])->name('approveVerificationKabKota');
        Route::post('/regency/{rtk}/approve-document', [RTKApprovalProvinceController::class, 'approveDocumentKabKota'])->name('approveDocumentKabKota');
        Route::post('/regency/{rtk}/reject', [RTKApprovalProvinceController::class, 'rejectKabKota'])->name('rejectKabKota');
    });
});

Route::prefix('admin-kab-kota')->middleware(['auth', 'role:admin-kab-kota'])->name('admin-kab-kota.')->group(function () {
    Route::resource('rencana-tenaga-kerja-daerah-kab-kota', RencanaTenagaKerjaKabKotaControllerAdminKabKota::class)
        ->parameters(['rencana-tenaga-kerja-daerah-kab-kota' => 'rtkd',])
        ->names('rtkd');

    Route::get('/regency/export', [ExportRtkRegencyController::class, 'ExportRtkRegency'])->name('export-regency');

    Route::prefix('laporan')->name('laporan.')->controller(RtkKabKotaDashboardController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });
});

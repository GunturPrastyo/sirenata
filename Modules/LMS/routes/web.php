<?php

use Illuminate\Support\Facades\Route;
use Modules\LMS\Http\Controllers\AdminProvince\RekapitulasiController as AdminProvinceRekapitulasiController;
use Modules\LMS\Http\Controllers\AdminKabKota\RekapitulasiController as AdminKabKotaRekapitulasiController;
use Modules\LMS\Http\Controllers\AdminPusat\RekapitulasiController;

Route::prefix('admin-pusat')->middleware(['auth', 'role:admin-pusat'])->name('admin-pusat.')->group(function () {
    Route::prefix('rekapitulasi')->name('rekapitulasi.')->controller(RekapitulasiController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/kab-kota/{provinceCode}', 'kabKota')->name('kab-kota');

        Route::get('/rekap-user-province/{provinceCode}', 'rekapUserProvince')->name('rekap-user-province');
        Route::get('/rekap-user-kab-kota/{regencyCode}', 'rekapUserKabKota')->name('rekap-user-kab-kota');
    });

    Route::resource('library-categories', \Modules\LMS\Http\Controllers\AdminPusat\LibraryCategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('libraries', \Modules\LMS\Http\Controllers\AdminPusat\LibraryController::class);

    Route::get('/certificates', [\Modules\LMS\Http\Controllers\AdminPusat\CertificateController::class, 'index'])->name('certificates.index');
    Route::post('/certificates', [\Modules\LMS\Http\Controllers\AdminPusat\CertificateController::class, 'store'])->name('certificates.store');
    Route::put('/certificates/{certificate}', [\Modules\LMS\Http\Controllers\AdminPusat\CertificateController::class, 'update'])->name('certificates.update');
    Route::patch('/certificates/{certificate}/activate', [\Modules\LMS\Http\Controllers\AdminPusat\CertificateController::class, 'activate'])->name('certificates.activate');
    Route::delete('/certificates/{certificate}', [\Modules\LMS\Http\Controllers\AdminPusat\CertificateController::class, 'destroy'])->name('certificates.destroy');
});

Route::prefix('admin-province')->middleware(['auth', 'role:admin-province'])->name('admin-province.')->group(function () {
    Route::prefix('rekapitulasi')->name('rekapitulasi.')->controller(AdminProvinceRekapitulasiController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/rekap-user-kab-kota/{regencyCode}', 'rekapUserKabKota')->name('rekap-user-kab-kota');
        Route::get('/rekap-user-province', 'rekapUserProvince')->name('rekap-user-province');
    });
});

Route::prefix('admin-kab-kota')->middleware(['auth', 'role:admin-kab-kota'])->name('admin-kab-kota.')->group(function () {
    Route::prefix('rekapitulasi')->name('rekapitulasi.')->controller(AdminKabKotaRekapitulasiController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });
});

Route::prefix('user')->middleware(['auth', 'role:user'])->name('user.')->group(function () {
    Route::get('/library', [\Modules\LMS\Http\Controllers\User\LibraryController::class, 'index'])->name('library.index');
    Route::prefix('course')->name('course.')->controller(\Modules\LMS\Http\Controllers\User\CourseController::class)->group(function () {
        Route::get('/my-course', 'allMyCourse')->name('my-course');
        Route::get('/my-course/progress', 'myCourseProgress')->name('my-course.progress');
        Route::get('/my-course/finish', 'myCourseFinish')->name('my-course.finish');
        Route::get('/my-course/{slug}', 'myCourseDetail')->name('my-course.detail');
    });
});
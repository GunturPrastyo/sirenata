<?php

use Illuminate\Support\Facades\Route;
use Modules\Faq\Http\Controllers\AdminPusat\FaqController as AdminPusatFaqController;
use Modules\Faq\Http\Controllers\AdminPusat\HelpController as AdminPusatHelpController;
use Modules\Faq\Http\Controllers\AdminProvince\FaqController as AdminProvinceFaqController;
use Modules\Faq\Http\Controllers\AdminProvince\HelpController as AdminProvinceHelpController;
use Modules\Faq\Http\Controllers\AdminKabKota\FaqController as AdminKabKotaFaqController;
use Modules\Faq\Http\Controllers\AdminKabKota\HelpController as AdminKabKotaHelpController;
use Modules\Faq\Http\Controllers\User\HelpController as UserHelpController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Pusat Routes
    Route::prefix('admin-pusat')->middleware(['role:admin-pusat|super-admin'])->name('admin-pusat.')->group(function () {
        Route::resource('faqs', AdminPusatFaqController::class)->names('faq');
        Route::get('/help', [AdminPusatHelpController::class, 'index'])->name('help');
    });

    // Admin Provinsi Routes
    Route::prefix('admin-province')->middleware(['role:admin-province'])->name('admin-province.')->group(function () {
        Route::resource('faqs', AdminProvinceFaqController::class)->only(['index'])->names('faq');
        Route::get('/help', [AdminProvinceHelpController::class, 'index'])->name('help');
    });

    // Admin Kab/Kota Routes
    Route::prefix('admin-kab-kota')->middleware(['role:admin-kab-kota'])->name('admin-kab-kota.')->group(function () {
        Route::resource('faqs', AdminKabKotaFaqController::class)->only(['index'])->names('faq');
        Route::get('/help', [AdminKabKotaHelpController::class, 'index'])->name('help');
    });

    // User Routes
    Route::prefix('user')->middleware(['role:user'])->name('user.')->group(function () {
        Route::get('/help', [UserHelpController::class, 'index'])->name('help');
    });
});

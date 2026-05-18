<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin-pusat')->middleware(['role:admin-pusat|super-admin'])->name('admin-pusat.')->group(function () {
        Route::resource('faqs', \Modules\Faq\Http\Controllers\AdminPusat\FaqController::class)->names('faq');
        Route::get('/help', [\Modules\Faq\Http\Controllers\AdminPusat\HelpController::class, 'index'])->name('help');
    });

    Route::prefix('admin-province')->middleware(['role:admin-province'])->name('admin-province.')->group(function () {
        Route::resource('faqs', \Modules\Faq\Http\Controllers\AdminProvince\FaqController::class)->only(['index'])->names('faq');
        Route::get('/help', [\Modules\Faq\Http\Controllers\AdminProvince\HelpController::class, 'index'])->name('help');
    });

    Route::prefix('admin-kab-kota')->middleware(['role:admin-kab-kota'])->name('admin-kab-kota.')->group(function () {
        Route::resource('faqs', \Modules\Faq\Http\Controllers\AdminKabKota\FaqController::class)->only(['index'])->names('faq');
        Route::get('/help', [\Modules\Faq\Http\Controllers\AdminKabKota\HelpController::class, 'index'])->name('help');
    });

    Route::prefix('user')->middleware(['role:user'])->name('user.')->group(function () {
        Route::get('/help', [\Modules\Faq\Http\Controllers\User\HelpController::class, 'index'])->name('help');
    });
});

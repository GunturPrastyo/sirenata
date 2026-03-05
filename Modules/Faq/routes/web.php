<?php

use Illuminate\Support\Facades\Route;
use Modules\Faq\Http\Controllers\FaqController;

Route::middleware(['auth', 'verified'])->group(function () {
    // View access — all admin roles can view
    Route::middleware(['role:admin-pusat|admin-province|admin-kab-kota'])->group(function () {
        Route::get('faqs', [FaqController::class, 'index'])->name('faq.index');
    });

    // Create, Edit, Delete — Admin Pusat only
    Route::middleware('role:admin-pusat')->group(function () {
        Route::post('faqs', [FaqController::class, 'store'])->name('faq.store');
        Route::put('faqs/{faq}', [FaqController::class, 'update'])->name('faq.update');
        Route::delete('faqs/{faq}', [FaqController::class, 'destroy'])->name('faq.destroy');
    });

    // Help Routes (Read-only view for end users and admins)
    Route::get('faq-help', [\Modules\Faq\Http\Controllers\HelpController::class, 'index'])->name('faq.help');

    Route::prefix('super-admin')->name('super-admin.')->group(function () {
        Route::get('/help', [\Modules\Faq\Http\Controllers\HelpController::class, 'index'])->name('help');
    });

    Route::prefix('admin-province')->name('admin-province.')->group(function () {
        Route::get('/help', [\Modules\Faq\Http\Controllers\HelpController::class, 'index'])->name('help');
    });

    Route::prefix('admin-kab-kota')->name('admin-kab-kota.')->group(function () {
        Route::get('/help', [\Modules\Faq\Http\Controllers\HelpController::class, 'index'])->name('help');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/help', [\Modules\Faq\Http\Controllers\HelpController::class, 'index'])->name('help');
    });
});

<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
// Admin Pusat
Route::prefix('admin-pusat')->middleware(['auth', 'role:admin-pusat|super-admin'])->name('admin-pusat.')->group(function () {
    Route::resource('projects', \Modules\Project\Http\Controllers\AdminPusat\ProjectController::class)->names('project');
});

// Admin Provinsi
Route::prefix('admin-province')->middleware(['auth', 'role:admin-province'])->name('admin-province.')->group(function () {
    Route::resource('projects', \Modules\Project\Http\Controllers\AdminProvince\ProjectController::class)->names('project');
});

// Admin Kab / Kota
Route::prefix('admin-kab-kota')->middleware(['auth', 'role:admin-kab-kota'])->name('admin-kab-kota.')->group(function () {
    Route::resource('projects', \Modules\Project\Http\Controllers\AdminKabKota\ProjectController::class)->names('project');
});
});

<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
Route::prefix('admin-pusat')->middleware(['auth', 'role:admin-pusat|super-admin'])->name('admin-pusat.')->group(function () {
    Route::get('projects/export', [\Modules\Project\Http\Controllers\AdminPusat\ProjectController::class, 'export'])->name('project.export');
    Route::resource('projects', \Modules\Project\Http\Controllers\AdminPusat\ProjectController::class)->names('project');
});

Route::prefix('admin-province')->middleware(['auth', 'role:admin-province'])->name('admin-province.')->group(function () {
    Route::resource('projects', \Modules\Project\Http\Controllers\AdminProvince\ProjectController::class)->names('project');
});

Route::prefix('admin-kab-kota')->middleware(['auth', 'role:admin-kab-kota'])->name('admin-kab-kota.')->group(function () {
    Route::resource('projects', \Modules\Project\Http\Controllers\AdminKabKota\ProjectController::class)->names('project');
});

Route::prefix('user')->middleware(['auth', 'role:user'])->name('user.')->group(function () {
    Route::get('kalkulator', [\Modules\Project\Http\Controllers\User\KalkulatorController::class, 'sandbox'])->name('kalkulator.sandbox');
    Route::get('kalkulator/proyek/{projectId}', [\Modules\Project\Http\Controllers\User\KalkulatorController::class, 'project'])->name('kalkulator.project');
    Route::get('tim-kerja', [\Modules\Project\Http\Controllers\User\TimKerjaController::class, 'index'])->name('tim-kerja.index');
});

Route::middleware(['auth'])->prefix('api/rtk')->group(function () {
    Route::post('save', [\Modules\Project\Http\Controllers\User\KalkulatorController::class, 'save'])->name('rtk.save');
    Route::get('load', [\Modules\Project\Http\Controllers\User\KalkulatorController::class, 'load'])->name('rtk.load');
    Route::get('sessions', [\Modules\Project\Http\Controllers\User\KalkulatorController::class, 'sessions'])->name('rtk.sessions');
    Route::post('delete', [\Modules\Project\Http\Controllers\User\KalkulatorController::class, 'delete'])->name('rtk.delete');
});
});

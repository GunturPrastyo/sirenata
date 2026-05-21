<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterData\Http\Controllers\MasterDataController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('masterdatas', MasterDataController::class)->names('masterdata');
});

// SuperAdmin Routes
Route::middleware(['auth', 'verified', 'role:super-admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::patch('lembaga/{lembaga}/toggle-status', [\Modules\MasterData\Http\Controllers\SuperAdmin\LembagaController::class, 'toggleStatus'])->name('lembaga.toggle-status');
    Route::patch('instansi/{instansi}/toggle-status', [\Modules\MasterData\Http\Controllers\SuperAdmin\InstansiController::class, 'toggleStatus'])->name('instansi.toggle-status');

    Route::resource('lembaga', \Modules\MasterData\Http\Controllers\SuperAdmin\LembagaController::class);
    Route::resource('instansi', \Modules\MasterData\Http\Controllers\SuperAdmin\InstansiController::class);
});

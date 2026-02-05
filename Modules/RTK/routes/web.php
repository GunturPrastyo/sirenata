<?php

use Illuminate\Support\Facades\Route;
use Modules\RTK\Http\Controllers\RencanaTenagaKerjaNasionalController;

Route::prefix('admin-pusat')->middleware(['auth', 'role:admin-pusat'])->name('admin-pusat.')->group(function () {
    Route::resource('rencana-tenaga-kerja-nasional', RencanaTenagaKerjaNasionalController::class)->names('rtkn');
});
<?php

use Illuminate\Support\Facades\Route;
use Modules\MasterData\Http\Controllers\Api\InstitutionApiController;

Route::prefix('masterdata')->name('masterdata.')->group(function () {
    Route::get('/institutions', [InstitutionApiController::class, 'index'])->name('institutions.index');
});

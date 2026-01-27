<?php

use Illuminate\Support\Facades\Route;
use Modules\LandingPage\Http\Controllers\LandingPageController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('landingpages', LandingPageController::class)->names('landingpage');
});

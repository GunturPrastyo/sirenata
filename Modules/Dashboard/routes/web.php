<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;
use Modules\Dashboard\Http\Controllers\PortalDashboardController;

Route::get('/portal-dashboard', [PortalDashboardController::class, 'index'])->name('portal-dashboard');
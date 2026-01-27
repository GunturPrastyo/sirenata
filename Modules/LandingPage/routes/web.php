<?php

use Illuminate\Support\Facades\Route;
use Modules\LandingPage\Http\Controllers\HomeController;
use Modules\LandingPage\Http\Controllers\LandingPageController;

// Route::resource('landingpages', LandingPageController::class)->names('landingpage');

Route::get('/', [HomeController::class, 'index'])->name('landingpage.index');

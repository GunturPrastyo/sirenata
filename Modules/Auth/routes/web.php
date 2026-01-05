<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\RegisterController;

Route::prefix("auth")->group(function () {

    Route::middleware("guest")->group(function () {
        Route::get("/login", [LoginController::class,"login"])->name("login");
        Route::post("/login", [LoginController::class,"authenticate"])->name("authenticate");

        Route::get("/register", [RegisterController::class,"register"])->name("register");
        Route::post("/register", [RegisterController::class,"store"])->name("register.store");
    });

    Route::post("/logout", [LoginController::class,"logout"])
        ->middleware("auth")
        ->name("logout");
});
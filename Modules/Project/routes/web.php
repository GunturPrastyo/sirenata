<?php

use Illuminate\Support\Facades\Route;
use Modules\Project\Http\Controllers\ProjectController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Basic view access
    Route::middleware(['permission:project.view'])->group(function () {
        Route::get('projects', [ProjectController::class, 'index'])->name('project.index');
        Route::get('projects/{project}', [ProjectController::class, 'show'])->name('project.show');
    });

    // Create access
    Route::middleware(['permission:project.create'])->group(function () {
        Route::get('projects/create/new', [ProjectController::class, 'create'])->name('project.create');
        Route::post('projects', [ProjectController::class, 'store'])->name('project.store');
    });

    // Edit access
    Route::middleware(['permission:project.edit'])->group(function () {
        Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('project.edit');
        Route::put('projects/{project}', [ProjectController::class, 'update'])->name('project.update');
        // Route::patch('projects/{project}', [ProjectController::class, 'update'])->name('project.update');
    });

    // Delete access
    Route::middleware(['permission:project.delete'])->group(function () {
        Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('project.destroy');
    });
});

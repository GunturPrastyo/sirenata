<?php

use Illuminate\Support\Facades\Route;
use Modules\LMS\Http\Controllers\Api\Category\CategoryController;
use Modules\LMS\Http\Controllers\Api\CourseBenefitController;
use Modules\LMS\Http\Controllers\Api\CourseController;
use Modules\LMS\Http\Controllers\Api\CourseMentorController;
use Modules\LMS\Http\Controllers\Api\CourseSectionController;
use Modules\LMS\Http\Controllers\Api\CourseStudentController;
use Modules\LMS\Http\Controllers\Api\CourseTestimoniController;
use Modules\LMS\Http\Controllers\Api\SectionContentController;

Route::prefix('v1')->group(function () {

    // ── Public ──
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index']);
        Route::get('/{slug}', [CourseController::class, 'show']);
        Route::get('/{slug}/benefits', [CourseBenefitController::class, 'index']);
        Route::get('/{slug}/testimonis', [CourseTestimoniController::class, 'index']);
    });

    Route::prefix('courses/{slug}')->group(function () {
        Route::get('/sections', [CourseSectionController::class, 'index']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{category:slug}', [CategoryController::class, 'show']);
    });

    // ── Auth ──
    Route::middleware('auth:sanctum')->group(function () {
        // My courses — course yang diikuti user yang login
        Route::get('/my-courses', [CourseStudentController::class, 'myCourses']);
        
        Route::prefix('sections/{courseSection}')->group(function () {
            Route::get('/contents', [SectionContentController::class, 'index']);
        });

        Route::prefix('contents')->group(function () {
            Route::get('/{content}', [SectionContentController::class, 'show']);
        });

        Route::prefix('courses/{slug}')->group(function () {
            Route::post('/testimonis', [CourseTestimoniController::class, 'store']);

            // Student enroll / unenroll
            Route::post('/enroll', [CourseStudentController::class, 'enroll']);
            Route::delete('/unenroll', [CourseStudentController::class, 'unenroll']);

            // Mentors (public read)
            Route::get('/mentors', [CourseMentorController::class, 'index']);
        });

        Route::prefix('testimonis')->group(function () {
            Route::put('/{testimoni}', [CourseTestimoniController::class, 'update']);
            Route::delete('/{testimoni}', [CourseTestimoniController::class, 'destroy']);
        });

        // ── admin-pusat 
        Route::middleware('role:admin-pusat')->group(function () {
            Route::prefix('courses')->group(function () {
                Route::post('/', [CourseController::class, 'store']);
                Route::put('/{slug}', [CourseController::class, 'update']);
                Route::delete('/{slug}', [CourseController::class, 'destroy']);

                Route::post('/{slug}/benefits', [CourseBenefitController::class, 'store']);
                Route::put('/benefits/{benefit}', [CourseBenefitController::class, 'update']);
                Route::delete('/benefits/{benefit}', [CourseBenefitController::class, 'destroy']);
            });

            Route::prefix('courses/{slug}')->group(function () {
                // Students management
                Route::get('/students', [CourseStudentController::class, 'index']);
                Route::patch('/students/{userId}/status', [CourseStudentController::class, 'updateStatus']);

                // Mentors management
                Route::post('/mentors', [CourseMentorController::class, 'store']);
                Route::patch('/mentors/{userId}/activate', [CourseMentorController::class, 'toggleMentorActivation']);
                Route::delete('/mentors/{userId}', [CourseMentorController::class, 'destroy']);
            });

            // Sections
            Route::prefix('courses/{slug}/sections')->group(function () {
                Route::post('/', [CourseSectionController::class, 'store']);
                Route::patch('/reorder', [CourseSectionController::class, 'reorder']);
            });

            Route::prefix('sections')->group(function () {
                Route::put('/{section}', [CourseSectionController::class, 'update']);
                Route::delete('/{section}', [CourseSectionController::class, 'destroy']);
            });

            // Contents
            Route::prefix('sections/{courseSection}/contents')->group(function () {
                Route::post('/', [SectionContentController::class, 'store']);
                Route::patch('/reorder', [SectionContentController::class, 'reorder']);
            });

            Route::prefix('contents')->group(function () {
                Route::put('/{content}', [SectionContentController::class, 'update']);
                Route::delete('/{content}', [SectionContentController::class, 'destroy']);
            });

            Route::prefix('categories')->group(function () {
                Route::post('/', [CategoryController::class, 'store']);
                Route::put('/{category:slug}/update', [CategoryController::class, 'update']);
                Route::delete('/{category:slug}/delete', [CategoryController::class, 'destroy']);
            });
        });
    });
});
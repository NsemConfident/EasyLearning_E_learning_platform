<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\PastQuestionController;
use App\Http\Controllers\Api\PastQuestionAnswerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Admin\PastQuestionController as AdminPastQuestionController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // Categories (reusable for courses & past questions)
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);

    // Courses
    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses/{course}', [CourseController::class, 'show']);
    Route::post('courses/{course}/enroll', [CourseController::class, 'enroll']);
    Route::get('courses/{course}/progress', [CourseController::class, 'progress']);

    // Lessons
    Route::post('lessons/{lesson}/complete', [LessonController::class, 'complete']);

    // Past questions (student)
    Route::get('past-questions', [PastQuestionController::class, 'index']);
    Route::get('past-questions/{pastQuestion}', [PastQuestionController::class, 'show']);
    Route::get('past-questions/search', [PastQuestionController::class, 'search']);
    Route::get('past-questions/{pastQuestion}/download', [PastQuestionController::class, 'download']);

    // Past question answers (answer PDFs linked to a past question)
    Route::get('past-questions/{pastQuestion}/answers', [PastQuestionAnswerController::class, 'index']);
    Route::get('past-questions/{pastQuestion}/answers/{answer}/download', [PastQuestionAnswerController::class, 'download']);

    // Admin past questions
    Route::prefix('admin')->middleware('can:managePastQuestions')->group(function () {
        Route::post('past-questions', [AdminPastQuestionController::class, 'store']);
        Route::put('past-questions/{pastQuestion}', [AdminPastQuestionController::class, 'update']);
        Route::delete('past-questions/{pastQuestion}', [AdminPastQuestionController::class, 'destroy']);
    });
});


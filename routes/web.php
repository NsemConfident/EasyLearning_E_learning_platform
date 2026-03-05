<?php

use App\Http\Controllers\Admin\PastQuestionDirectUploadController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

// Admin: direct PDF upload for Past Questions (fallback when Filament Livewire upload fails)
Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {
    Route::get('past-questions-direct-upload', [PastQuestionDirectUploadController::class, 'showForm'])
        ->name('admin.past-questions.direct-upload.form');
    Route::post('past-questions-direct-upload', [PastQuestionDirectUploadController::class, 'store'])
        ->name('admin.past-questions.direct-upload.store');
});

require __DIR__.'/settings.php';

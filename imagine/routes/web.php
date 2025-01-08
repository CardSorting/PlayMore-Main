<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/features', [HomeController::class, 'features'])->name('features');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');

// Authentication Routes
require __DIR__.'/auth.php';

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard redirect
    Route::get('/dashboard', function () {
        return redirect()->route('images.gallery');
    })->name('dashboard');

    // Image Generation Routes
    Route::prefix('dashboard')->name('images.')->group(function () {
        Route::get('/generate', [ImageController::class, 'index'])->name('create');
        Route::post('/generate', [ImageController::class, 'generate'])->name('generate');
        Route::get('/status/{taskId}', [ImageController::class, 'status'])->name('status');
        Route::get('/gallery', [ImageController::class, 'gallery'])->name('gallery');
        Route::get('/card/{id}', [ImageController::class, 'createCard'])->name('create-card');
        Route::post('/card', [ImageController::class, 'storeCard'])->name('store-card');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

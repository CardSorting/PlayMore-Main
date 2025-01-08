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
    });

    // Card Routes
    Route::prefix('dashboard')->name('cards.')->group(function () {
        Route::get('/cards', [CardController::class, 'index'])->name('index');
        Route::get('/cards/create', [CardController::class, 'create'])->name('create');
        Route::post('/cards', [CardController::class, 'store'])->name('store');
        Route::get('/cards/{card}', [CardController::class, 'show'])->name('show');
        Route::get('/cards/{card}/edit', [CardController::class, 'edit'])->name('edit');
        Route::put('/cards/{card}', [CardController::class, 'update'])->name('update');
        Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('destroy');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

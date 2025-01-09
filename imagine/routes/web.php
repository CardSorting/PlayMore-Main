<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CreditController;
use Illuminate\Support\Facades\{Route, Redis};

// Public Routes
// Test Redis connection
Route::get('/test-redis', function () {
    try {
        Redis::set('test_key', 'working');
        $value = Redis::get('test_key');
        return "Redis is working: " . $value;
    } catch (\Exception $e) {
        return "Redis error: " . $e->getMessage();
    }
});

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
    Route::controller(CardController::class)->prefix('dashboard/cards')->name('cards.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{card}', 'show')->name('show');
        Route::get('/{card}/edit', 'edit')->name('edit');
        Route::put('/{card}', 'update')->name('update');
        Route::delete('/{card}', 'destroy')->name('destroy');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Credit Routes
    Route::prefix('credits')->name('credits.')->group(function () {
        Route::get('/', [CreditController::class, 'getBalance'])->name('balance');
        Route::post('/add', [CreditController::class, 'addCredits'])->name('add');
        Route::post('/deduct', [CreditController::class, 'deductCredits'])->name('deduct');
        Route::get('/history', [CreditController::class, 'getHistory'])->name('history');
    });
});

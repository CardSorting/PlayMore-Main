<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\{Route, Redis};

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/features', [HomeController::class, 'features'])->name('features');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');

// System Health Check
Route::get('/test-redis', function () {
    try {
        Redis::set('test_key', 'working');
        $value = Redis::get('test_key');
        return "Redis is working: " . $value;
    } catch (\Exception $e) {
        return "Redis error: " . $e->getMessage();
    }
});

// Authentication Routes
require __DIR__.'/auth.php';

// Dashboard Routes (authenticated)
Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    require __DIR__.'/dashboard.php';
    require __DIR__.'/cards.php';
    require __DIR__.'/packs.php';
    require __DIR__.'/admin.php';

    // Profile Routes
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::get('/profile/orders/{order}', [ProfileController::class, 'showOrder'])->name('profile.orders.show');
});

// Marketplace Routes
require __DIR__.'/marketplace.php';

<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PulseController;
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
    
    // Payment Processing Routes
    Route::prefix('api')->group(function () {
        Route::get('/pulse', [PulseController::class, 'index'])->name('pulse.index');
        Route::post('/payment-intent', [PulseController::class, 'createPaymentIntent']);
        Route::post('/payment-intent/{paymentIntentId}/confirm', [PulseController::class, 'confirmPayment']);
    });
});

// Marketplace Routes
require __DIR__.'/marketplace.php';

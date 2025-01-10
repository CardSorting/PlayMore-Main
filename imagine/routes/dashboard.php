<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\PulseController;
use App\Http\Controllers\PrintOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard home
    Route::get('/', function () {
        return redirect()->route('images.gallery');
    })->name('dashboard');

    // Image Generation Routes
    Route::prefix('generate')->name('images.')->group(function () {
        Route::get('/', [ImageController::class, 'index'])->name('create');
        Route::post('/', [ImageController::class, 'generate'])
            ->middleware(\App\Http\Middleware\ImageGenerationRateLimiter::class)
            ->name('generate');
        Route::get('/status/{taskId}', [ImageController::class, 'status'])->name('status');
        Route::get('/gallery', [ImageController::class, 'gallery'])->name('gallery');
    });

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Credit Management
    Route::prefix('credits')->name('credits.')->group(function () {
        Route::get('/', [CreditController::class, 'getBalance'])->name('balance');
        Route::post('/add', [CreditController::class, 'addCredits'])->name('add');
        Route::post('/deduct', [CreditController::class, 'deductCredits'])->name('deduct');
        Route::get('/history', [CreditController::class, 'getHistory'])->name('history');
    });

    // Payment Processing Routes
    Route::prefix('api')->group(function () {
        Route::get('/pulse', [PulseController::class, 'index'])->name('pulse.index');
        Route::post('/payment-intent', [PulseController::class, 'createPaymentIntent'])
            ->withoutMiddleware(['auth', 'verified']);
        Route::post('/payment-intent/{paymentIntentId}/confirm', [PulseController::class, 'confirmPayment'])
            ->withoutMiddleware(['auth', 'verified']);
    });

    // Print Order Routes
    Route::prefix('prints')->name('prints.')->group(function () {
        Route::get('/', [PrintOrderController::class, 'index'])->name('index');
        Route::get('/gallery/{gallery}/create', [PrintOrderController::class, 'create'])->name('create');
        Route::post('/gallery/{gallery}', [PrintOrderController::class, 'store'])->name('store');
        Route::get('/orders/{printOrder}/checkout', [PrintOrderController::class, 'checkout'])->name('checkout');
        Route::post('/orders/{printOrder}/confirm', [PrintOrderController::class, 'confirm'])->name('confirm');
        Route::get('/orders/{printOrder}/success', [PrintOrderController::class, 'success'])->name('success');
    });
});

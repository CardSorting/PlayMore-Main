<?php

use App\Http\Controllers\PrintOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // Print Order Routes
    Route::prefix('dashboard/prints')->name('prints.')->group(function () {
        // List all print orders
        Route::get('/', [PrintOrderController::class, 'index'])
            ->name('index');

        // Create new print order
        Route::get('/gallery/{gallery}/create', [PrintOrderController::class, 'create'])
            ->name('create');
        Route::post('/gallery/{gallery}', [PrintOrderController::class, 'store'])
            ->name('store');

        // Print order management
        Route::middleware('print.access')->group(function () {
            // View print order
            Route::get('/{order}', [PrintOrderController::class, 'show'])
                ->name('show');

            // Checkout process
            Route::get('/{order}/checkout', [PrintOrderController::class, 'checkout'])
                ->name('checkout');
            Route::post('/{order}/process-payment', [PrintOrderController::class, 'processPayment'])
                ->name('process-payment');
            Route::get('/{order}/success', [PrintOrderController::class, 'success'])
                ->name('success');

            // Order actions
            Route::post('/{order}/cancel', [PrintOrderController::class, 'cancel'])
                ->name('cancel');
            Route::post('/{order}/reorder', [PrintOrderController::class, 'reorder'])
                ->name('reorder');
        });
    });
});

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::prefix('admin/prints')->name('admin.prints.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PrintOrderController::class, 'index'])
            ->name('index');
        Route::get('/{order}', [\App\Http\Controllers\Admin\PrintOrderController::class, 'show'])
            ->name('show');
        Route::post('/{order}/update-status', [\App\Http\Controllers\Admin\PrintOrderController::class, 'updateStatus'])
            ->name('update-status');
        Route::post('/{order}/add-tracking', [\App\Http\Controllers\Admin\PrintOrderController::class, 'addTracking'])
            ->name('add-tracking');
        Route::post('/{order}/refund', [\App\Http\Controllers\Admin\PrintOrderController::class, 'refund'])
            ->name('refund');
    });
});

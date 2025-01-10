<?php

use App\Http\Controllers\PrintOrderController;
use App\Http\Controllers\Admin\PrintOrderController as AdminPrintOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // Print Order Routes
    Route::prefix('prints')->name('prints.')->group(function () {
        // List all print orders
        Route::get('/', [PrintOrderController::class, 'index'])
            ->name('index');

        // Multi-step print order creation
        Route::get('/gallery/{gallery}/create', [PrintOrderController::class, 'create'])
            ->name('create');

        Route::get('/gallery/{gallery}', [PrintOrderController::class, 'overview'])
            ->name('overview');

        Route::get('/gallery/{gallery}/size', [PrintOrderController::class, 'selectSize'])
            ->name('select-size');

        Route::post('/gallery/{gallery}/size', [PrintOrderController::class, 'storeSize'])
            ->name('store-size');

        Route::get('/gallery/{gallery}/material', [PrintOrderController::class, 'selectMaterial'])
            ->name('select-material');

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
                ->name('cancel')
                ->middleware('can:cancel,order');

            Route::post('/{order}/reorder', [PrintOrderController::class, 'reorder'])
                ->name('reorder')
                ->middleware('can:reorder,order');

            // Tracking and documents
            Route::get('/{order}/track', [PrintOrderController::class, 'track'])
                ->name('track')
                ->middleware('can:track,order');

            Route::get('/{order}/invoice', [PrintOrderController::class, 'downloadInvoice'])
                ->name('invoice')
                ->middleware('can:downloadInvoice,order');
        });
    });
});

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::prefix('admin/prints')->name('admin.prints.')->group(function () {
        // List and search orders
        Route::get('/', [AdminPrintOrderController::class, 'index'])
            ->name('index');

        // View order details
        Route::get('/{order}', [AdminPrintOrderController::class, 'show'])
            ->name('show');

        // Order management
        Route::post('/{order}/update-status', [AdminPrintOrderController::class, 'updateStatus'])
            ->name('update-status');

        Route::post('/{order}/add-tracking', [AdminPrintOrderController::class, 'addTracking'])
            ->name('add-tracking');

        Route::post('/{order}/refund', [AdminPrintOrderController::class, 'refund'])
            ->name('refund');

        // Batch operations
        Route::post('/batch/update-status', [AdminPrintOrderController::class, 'batchUpdateStatus'])
            ->name('batch.update-status');

        Route::post('/batch/export', [AdminPrintOrderController::class, 'batchExport'])
            ->name('batch.export');
    });
});

// API Routes (for admin dashboard)
Route::middleware(['auth:sanctum', 'verified', 'admin'])->group(function () {
    Route::prefix('api/admin/prints')->name('api.admin.prints.')->group(function () {
        Route::get('/stats', [AdminPrintOrderController::class, 'stats'])
            ->name('stats');
            
        Route::get('/search', [AdminPrintOrderController::class, 'search'])
            ->name('search');
    });
});

<?php

use App\Http\Controllers\Admin\PrintOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Print Orders
    Route::prefix('prints')->name('prints.')->group(function () {
        Route::get('/', [PrintOrderController::class, 'index'])->name('index');
        Route::get('/orders/{order}', [PrintOrderController::class, 'show'])->name('show');
        Route::post('/orders/{order}/status', [PrintOrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/orders/bulk-status', [PrintOrderController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
        Route::get('/export', [PrintOrderController::class, 'export'])->name('export');
    });
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    // User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Print Orders API
    Route::prefix('prints')->name('api.prints.')->group(function () {
        // Customer endpoints
        Route::get('/', [\App\Http\Controllers\Api\PrintOrderController::class, 'index'])
            ->name('index');
        Route::get('/{order}', [\App\Http\Controllers\Api\PrintOrderController::class, 'show'])
            ->name('show');
        Route::post('/gallery/{gallery}', [\App\Http\Controllers\Api\PrintOrderController::class, 'store'])
            ->name('store');
        Route::post('/{order}/payment', [\App\Http\Controllers\Api\PrintOrderController::class, 'processPayment'])
            ->name('process-payment');
        Route::post('/{order}/cancel', [\App\Http\Controllers\Api\PrintOrderController::class, 'cancel'])
            ->name('cancel');

        // Admin endpoints
        Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
            Route::get('/stats', [\App\Http\Controllers\Api\Admin\PrintOrderController::class, 'stats'])
                ->name('stats');
            Route::get('/search', [\App\Http\Controllers\Api\Admin\PrintOrderController::class, 'search'])
                ->name('search');
            Route::post('/{order}/status', [\App\Http\Controllers\Api\Admin\PrintOrderController::class, 'updateStatus'])
                ->name('update-status');
            Route::post('/{order}/tracking', [\App\Http\Controllers\Api\Admin\PrintOrderController::class, 'addTracking'])
                ->name('add-tracking');
            Route::post('/{order}/refund', [\App\Http\Controllers\Api\Admin\PrintOrderController::class, 'refund'])
                ->name('refund');
            Route::post('/batch/update-status', [\App\Http\Controllers\Api\Admin\PrintOrderController::class, 'batchUpdateStatus'])
                ->name('batch.update-status');
            Route::post('/batch/export', [\App\Http\Controllers\Api\Admin\PrintOrderController::class, 'batchExport'])
                ->name('batch.export');
        });
    });

    // Configuration endpoints
    Route::prefix('config')->name('api.config.')->group(function () {
        Route::get('/prints/sizes', function () {
            return response()->json(config('prints.sizes'));
        })->name('prints.sizes');

        Route::get('/shipping/zones', function () {
            return response()->json(config('location.shipping_zones'));
        })->name('shipping.zones');

        Route::get('/shipping/methods', function () {
            return response()->json(config('location.shipping_methods'));
        })->name('shipping.methods');
    });
});

// Public endpoints
Route::get('/prints/tracking/{number}', [\App\Http\Controllers\Api\PrintOrderController::class, 'tracking'])
    ->name('api.prints.tracking');

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'environment' => app()->environment(),
        'version' => config('app.version'),
    ]);
})->name('api.health');

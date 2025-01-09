<?php

use App\Http\Controllers\PackController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('dashboard/packs')
    ->name('packs.')
    ->controller(PackController::class)
    ->group(function () {
        // Pack listing and creation
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        
        // Individual pack management
        Route::prefix('{pack}')->group(function () {
            Route::get('/', 'show')->name('show');
            
            // Pack building (requires pack to not be sealed)
            Route::middleware('can:update,pack')->group(function () {
                Route::post('/add-card', 'addCard')->name('add-card');
                Route::post('/seal', 'seal')->name('seal');
            });
        });
    });

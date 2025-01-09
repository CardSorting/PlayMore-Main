<?php

use App\Marketplace\Controllers\Browse\BrowseMarketplaceController;
use App\Marketplace\Controllers\Seller\SellerDashboardController;
use App\Marketplace\Controllers\Purchase\PurchaseHistoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('marketplace')->name('marketplace.')->group(function () {
    // Browse Marketplace
    Route::controller(BrowseMarketplaceController::class)
        ->name('browse.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/filter', 'filter')->name('filter');
            Route::post('/packs/{pack}/purchase', 'purchasePack')
                ->middleware('throttle:marketplace-purchase')
                ->name('purchase');
        });

    // Seller Dashboard
    Route::controller(SellerDashboardController::class)
        ->prefix('seller')
        ->name('seller.')
        ->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard');
            Route::post('/packs/{pack}/list', 'listPack')
                ->middleware('throttle:marketplace-list')
                ->name('list');
            Route::post('/packs/{pack}/unlist', 'unlistPack')
                ->middleware('throttle:marketplace-list')
                ->name('unlist');
            Route::get('/sales', 'salesHistory')->name('sales');
        });

    // Purchase History
    Route::controller(PurchaseHistoryController::class)
        ->prefix('purchases')
        ->name('purchase.')
        ->group(function () {
            Route::get('/history', 'index')->name('history');
        });
});

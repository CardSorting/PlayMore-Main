<?php

use App\Marketplace\Controllers\Browse\BrowseMarketplaceController;
use App\Marketplace\Controllers\Seller\SellerDashboardController;
use App\Marketplace\Controllers\Purchase\PurchaseHistoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // Browse Marketplace
    Route::get('/marketplace', [BrowseMarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/filter', [BrowseMarketplaceController::class, 'filter'])->name('marketplace.filter');
    Route::post('/marketplace/packs/{pack}/purchase', [BrowseMarketplaceController::class, 'purchasePack'])
        ->middleware('throttle:marketplace-purchase')
        ->name('marketplace.purchase');

    // Seller Dashboard
    Route::get('/marketplace/seller', [SellerDashboardController::class, 'index'])->name('marketplace.seller.dashboard');
    Route::post('/marketplace/seller/packs/{pack}/list', [SellerDashboardController::class, 'listPack'])
        ->middleware('throttle:marketplace-list')
        ->name('marketplace.seller.list');
    Route::post('/marketplace/seller/packs/{pack}/unlist', [SellerDashboardController::class, 'unlistPack'])
        ->middleware('throttle:marketplace-list')
        ->name('marketplace.seller.unlist');
    Route::get('/marketplace/seller/sales', [SellerDashboardController::class, 'salesHistory'])->name('marketplace.seller.sales');

    // Purchase History
    Route::get('/marketplace/purchases', [PurchaseHistoryController::class, 'index'])->name('marketplace.purchase.history');
});

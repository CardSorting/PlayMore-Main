<?php

use App\Marketplace\Controllers\Browse\BrowseMarketplaceController;
use App\Marketplace\Controllers\Seller\SellerDashboardController;
use App\Marketplace\Controllers\Purchase\PurchaseHistoryController;
use Illuminate\Support\Facades\Route;

// Browse Marketplace
Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::get('/marketplace', [BrowseMarketplaceController::class, 'index'])->name('marketplace.index');
    
    Route::post('/marketplace/packs/{pack}/purchase', [BrowseMarketplaceController::class, 'purchasePack'])
        ->name('marketplace.purchase');

    // Seller Dashboard
    Route::get('/marketplace/seller', [SellerDashboardController::class, 'index'])->name('marketplace.seller.dashboard');
    
    Route::post('/marketplace/seller/packs/{pack}/list', [SellerDashboardController::class, 'listPack'])
        ->name('marketplace.seller.list');
    
    Route::post('/marketplace/seller/packs/{pack}/unlist', [SellerDashboardController::class, 'unlistPack'])
        ->name('marketplace.seller.unlist');
    
    Route::get('/marketplace/seller/sales', [SellerDashboardController::class, 'salesHistory'])->name('marketplace.seller.sales');

    // Purchase History
    Route::get('/marketplace/purchases', [PurchaseHistoryController::class, 'index'])->name('marketplace.purchase.history');
});

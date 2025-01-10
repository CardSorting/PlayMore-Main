<?php

use App\Http\Controllers\PublicGalleryController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

Route::get('/{user:name}', [PublicGalleryController::class, 'store'])
    ->name('public.gallery.store');

Route::get('/{user:name}/gallery/{gallery}', [PublicGalleryController::class, 'show'])
    ->name('public.gallery.show');

// Rating routes
Route::middleware('auth')->group(function () {
    Route::post('/{user:name}/reviews', [RatingController::class, 'store'])
        ->name('public.reviews.store');
    Route::put('/{user:name}/reviews/{rating}', [RatingController::class, 'update'])
        ->name('public.reviews.update');
    Route::delete('/{user:name}/reviews/{rating}', [RatingController::class, 'destroy'])
        ->name('public.reviews.destroy');
});

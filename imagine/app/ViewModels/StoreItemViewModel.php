<?php

namespace App\ViewModels;

use App\Models\User;
use App\Models\Gallery;
use App\Services\StoreService;

class StoreItemViewModel
{
    private StoreService $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function forShow(User $user, Gallery $gallery): array
    {
        // Verify the gallery belongs to the user and is available
        if ($gallery->user_id !== $user->id || !$gallery->is_available) {
            abort(404);
        }

        // Increment view count
        $gallery->incrementViews();

        // Update user's last active timestamp
        $user->updateLastActive();

        return [
            'gallery' => $gallery->load(['user', 'printOrders']),
            'user' => $user->load('ratings'),
            'sellerInfo' => $this->storeService->getSellerInfo($user),
            'stats' => $this->storeService->getSellerStats($user),
            'similarItems' => $this->storeService->getSimilarItems($user, $gallery->type, $gallery->id)
        ];
    }
}

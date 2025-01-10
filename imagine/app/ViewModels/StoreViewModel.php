<?php

namespace App\ViewModels;

use App\Models\User;
use App\Services\StoreService;
use Illuminate\Http\Request;
use App\DTOs\Store\StoreFiltersDTO;

class StoreViewModel
{
    private StoreService $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function forStore(Request $request, User $user): array
    {
        // Get item counts
        $counts = $this->storeService->getItemCounts($user);
        
        // Get price ranges for current tab
        $priceRanges = $this->storeService->getPriceRanges($user, $request->get('tab', 'prints'));

        // Create filters DTO
        $filters = StoreFiltersDTO::fromRequest($request, $priceRanges);

        return [
            'user' => $user,
            'sellerInfo' => $this->storeService->getSellerInfo($user),
            'stats' => $this->storeService->getSellerStats($user),
            'items' => $this->storeService->getStoreItems($user, $filters),
            'activeTab' => $filters->tab,
            'totalPrints' => $counts['prints'],
            'totalCards' => $counts['cards'],
            'filters' => $filters,
            'reviews' => $this->storeService->getReviews($user)
        ];
    }
}

<?php

namespace App\Marketplace\Services\Browse;

use App\Models\Pack;
use App\Models\User;
use App\Services\PulseService;
use Illuminate\Support\Facades\DB;

class BrowseMarketplaceService
{
    protected $pulseService;

    public function __construct(PulseService $pulseService)
    {
        $this->pulseService = $pulseService;
    }

    public function getAvailablePacks(): \Illuminate\Database\Eloquent\Collection
    {
        return Pack::availableOnMarketplace()
            ->withCount('cards')
            ->with([
                'cards' => function($query) {
                    $query->select('id', 'pack_id', 'image_url')
                        ->inRandomOrder()
                        ->limit(1);
                },
                'user:id,name'
            ])
            ->latest('listed_at')
            ->get();
    }

    public function purchasePack(Pack $pack, User $buyer): array
    {
        if (!$pack->canBePurchased() || $pack->user_id === $buyer->id) {
            return [
                'success' => false,
                'message' => 'Pack is not available for purchase'
            ];
        }

        if (!$this->pulseService->deductCredits($buyer, $pack->price, 'Purchase pack #' . $pack->id)) {
            return [
                'success' => false,
                'message' => 'Insufficient credits'
            ];
        }

        try {
            DB::beginTransaction();

            // Credit the seller
            $this->pulseService->addCredits(
                $pack->user, 
                $pack->price, 
                'Sold pack #' . $pack->id
            );

            // Transfer ownership
            $pack->update([
                'user_id' => $buyer->id,
                'is_listed' => false,
                'listed_at' => null
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Pack purchased successfully'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Refund the buyer
            $this->pulseService->addCredits(
                $buyer, 
                $pack->price, 
                'Refund for failed purchase of pack #' . $pack->id
            );

            return [
                'success' => false,
                'message' => 'Failed to process purchase'
            ];
        }
    }

}

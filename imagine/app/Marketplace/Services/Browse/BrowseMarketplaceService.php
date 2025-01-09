<?php

namespace App\Marketplace\Services\Browse;

use App\Models\Pack;
use App\Models\User;
use App\Services\PulseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class BrowseMarketplaceService
{
    protected $pulseService;
    const CACHE_TTL = 300; // 5 minutes
    const PER_PAGE = 12;

    public function __construct(PulseService $pulseService)
    {
        $this->pulseService = $pulseService;
    }

    public function getAvailablePacks(array $filters = [], int $page = 1): LengthAwarePaginator
    {
        $cacheKey = $this->buildCacheKey($filters, $page);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($filters, $page) {
            $query = Pack::availableOnMarketplace()
                ->withCount('cards')
                ->with([
                    'cards' => function($query) {
                        $query->select('id', 'pack_id', 'image_url')
                            ->inRandomOrder()
                            ->limit(1);
                    },
                    'user:id,name',
                    'creditTransactions' => function($query) {
                        $query->select('id', 'pack_id', 'amount', 'created_at')
                            ->latest()
                            ->limit(1);
                    }
                ])
                ->latest('listed_at');

            // Apply filters
            if (!empty($filters['min_price'])) {
                $query->where('price', '>=', $filters['min_price']);
            }
            if (!empty($filters['max_price'])) {
                $query->where('price', '<=', $filters['max_price']);
            }
            if (!empty($filters['min_cards'])) {
                $query->has('cards', '>=', $filters['min_cards']);
            }
            if (!empty($filters['sort'])) {
                switch ($filters['sort']) {
                    case 'price_asc':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('price', 'desc');
                        break;
                    case 'cards_asc':
                        $query->orderBy('cards_count', 'asc');
                        break;
                    case 'cards_desc':
                        $query->orderBy('cards_count', 'desc');
                        break;
                    default:
                        $query->latest('listed_at');
                }
            }

            return $query->paginate(
                perPage: self::PER_PAGE,
                page: $page
            );
        });
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

            // Clear cache after successful purchase
            $this->clearListingsCache();

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

    protected function buildCacheKey(array $filters, int $page): string
    {
        $key = 'marketplace_listings:page_' . $page;
        
        if (!empty($filters)) {
            $key .= ':' . md5(json_encode($filters));
        }

        return $key;
    }

    public function clearListingsCache(): void
    {
        Cache::tags(['marketplace_listings'])->flush();
    }

    public function getMarketplaceStats(): array
    {
        return Cache::remember('marketplace_stats', self::CACHE_TTL, function () {
            return [
                'total_listings' => Pack::availableOnMarketplace()->count(),
                'avg_price' => Pack::availableOnMarketplace()->avg('price'),
                'total_volume' => DB::table('credit_transactions')
                    ->where('description', 'like', 'Purchase pack #%')
                    ->sum('amount'),
                'recent_sales' => DB::table('credit_transactions')
                    ->where('description', 'like', 'Purchase pack #%')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
            ];
        });
    }
}

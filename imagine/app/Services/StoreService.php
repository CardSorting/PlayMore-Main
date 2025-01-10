<?php

namespace App\Services;

use App\Models\User;
use App\DTOs\Store\SellerInfoDTO;
use App\DTOs\Store\SellerStatsDTO;
use App\DTOs\Store\StoreFiltersDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class StoreService
{
    public function getSellerInfo(User $user): SellerInfoDTO
    {
        return SellerInfoDTO::fromUser($user);
    }

    public function getSellerStats(User $user): SellerStatsDTO
    {
        return SellerStatsDTO::fromUser($user);
    }

    public function getStoreItems(User $user, StoreFiltersDTO $filters): LengthAwarePaginator
    {
        // Base query with eager loading
        $query = $user->galleries()
            ->with(['user', 'printOrders'])
            ->where('type', $filters->tab === 'prints' ? 'image' : 'card')
            ->available();

        // Apply search if provided
        if ($filters->search) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters->search}%")
                  ->orWhere('prompt', 'like', "%{$filters->search}%");
            });
        }

        // Apply price filters
        if ($filters->priceMin) {
            $query->where('price', '>=', $filters->priceMin);
        }
        if ($filters->priceMax) {
            $query->where('price', '<=', $filters->priceMax);
        }

        // Apply sorting
        switch ($filters->sort) {
            case 'popular':
                $query->byPopularity();
                break;
            case 'price_low':
                $query->byPrice('asc');
                break;
            case 'price_high':
                $query->byPrice('desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        return $query->paginate(12)->withQueryString();
    }

    public function getPriceRanges(User $user, string $type): object
    {
        return $user->galleries()
            ->where('type', $type === 'prints' ? 'image' : 'card')
            ->available()
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();
    }

    public function getItemCounts(User $user): array
    {
        return [
            'prints' => $user->galleries()->where('type', 'image')->available()->count(),
            'cards' => $user->galleries()->where('type', 'card')->available()->count()
        ];
    }

    public function getSimilarItems(User $user, string $type, int $currentId, int $limit = 4): array
    {
        return $user->galleries()
            ->where('id', '!=', $currentId)
            ->where('type', $type)
            ->available()
            ->latest()
            ->take($limit)
            ->get()
            ->toArray();
    }

    public function getReviews(User $user): LengthAwarePaginator
    {
        return $user->ratings()
            ->with('rater')
            ->latest()
            ->paginate(5);
    }
}

<?php

namespace App\Marketplace\Services\Seller;

use App\Models\Pack;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SellerDashboardService
{
    public function getListedPacks(User $user)
    {
        return Pack::where('user_id', $user->id)
            ->where('is_listed', true)
            ->withCount('cards')
            ->with(['cards' => function($query) {
                $query->inRandomOrder()->limit(1);
            }])
            ->latest('listed_at')
            ->get();
    }

    public function getSoldPacks(User $user)
    {
        return Pack::where('user_id', '!=', $user->id)
            ->whereIn('id', function($query) use ($user) {
                $query->select('pack_id')
                    ->from('credit_transactions')
                    ->where('user_id', $user->id)
                    ->where('description', 'like', 'Sold pack #%');
            })
            ->with('user')
            ->latest()
            ->get();
    }

    public function getAvailablePacks(User $user)
    {
        return Pack::where('user_id', $user->id)
            ->where('is_sealed', true)
            ->where('is_listed', false)
            ->withCount('cards')
            ->with(['cards' => function($query) {
                $query->inRandomOrder()->limit(1);
            }])
            ->latest()
            ->get();
    }

    public function listPack(Pack $pack, int $price): bool
    {
        if (!$pack->is_sealed) {
            return false;
        }

        try {
            DB::beginTransaction();

            $pack->update([
                'is_listed' => true,
                'listed_at' => now(),
                'price' => $price
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function unlistPack(Pack $pack): bool
    {
        if (!$pack->is_listed) {
            return false;
        }

        try {
            DB::beginTransaction();

            $pack->update([
                'is_listed' => false,
                'listed_at' => null,
                'price' => null
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function getTotalSales(User $user): int
    {
        return DB::table('credit_transactions')
            ->where('user_id', $user->id)
            ->where('description', 'like', 'Sold pack #%')
            ->sum('amount');
    }

    public function getRecentSales(User $user, int $limit = 5)
    {
        return DB::table('credit_transactions')
            ->where('user_id', $user->id)
            ->where('description', 'like', 'Sold pack #%')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}

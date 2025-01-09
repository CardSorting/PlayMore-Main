<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Pack extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'is_sealed',
        'sealed_at',
        'is_listed',
        'listed_at',
        'price',
        'card_limit'
    ];

    protected $casts = [
        'is_sealed' => 'boolean',
        'is_listed' => 'boolean',
        'sealed_at' => 'datetime',
        'listed_at' => 'datetime',
        'price' => 'integer',
        'card_limit' => 'integer'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(GlobalCard::class);
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    // Marketplace Scopes
    public function scopeAvailableOnMarketplace(Builder $query): Builder
    {
        return $query->where('is_listed', true)
                    ->where('is_sealed', true)
                    ->whereNotNull('price')
                    ->whereNotNull('listed_at');
    }

    public function scopeListedByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId)
                    ->where('is_listed', true);
    }

    public function scopeSoldByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', '!=', $userId)
                    ->whereIn('id', function($query) use ($userId) {
                        $query->select('pack_id')
                            ->from('credit_transactions')
                            ->where('user_id', $userId)
                            ->where('description', 'like', 'Sold pack #%');
                    });
    }

    public function scopePurchasedByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId)
                    ->whereIn('id', function($query) use ($userId) {
                        $query->select('pack_id')
                            ->from('credit_transactions')
                            ->where('user_id', $userId)
                            ->where('description', 'like', 'Purchase pack #%');
                    });
    }

    // Helper Methods
    public function canBePurchased(): bool
    {
        return $this->is_listed && 
               $this->is_sealed && 
               $this->price > 0 && 
               $this->listed_at !== null;
    }

    public function canBeListed(): bool
    {
        return $this->is_sealed && 
               $this->sealed_at !== null &&
               !$this->is_listed &&
               $this->cards()->count() >= $this->card_limit;
    }

    public function seal(): bool
    {
        if ($this->is_sealed || $this->cards()->count() < $this->card_limit) {
            return false;
        }

        $this->update([
            'is_sealed' => true,
            'sealed_at' => now()
        ]);

        return true;
    }

    public function list(int $price): array
    {
        if (!$this->is_sealed || !$this->sealed_at) {
            return [
                'success' => false,
                'message' => 'Pack must be sealed before listing.'
            ];
        }

        if ($this->is_listed) {
            return [
                'success' => false,
                'message' => 'Pack is already listed on the marketplace.'
            ];
        }

        $cardCount = $this->cards()->count();
        if ($cardCount < $this->card_limit) {
            return [
                'success' => false,
                'message' => "Pack must be full before listing ({$cardCount}/{$this->card_limit} cards)."
            ];
        }

        $this->update([
            'is_listed' => true,
            'listed_at' => now(),
            'price' => $price
        ]);

        $this->clearMarketplaceCache();

        return [
            'success' => true,
            'message' => 'Pack has been listed successfully.'
        ];
    }

    public function unlist(): bool
    {
        if (!$this->is_listed) {
            return false;
        }

        $this->update([
            'is_listed' => false,
            'listed_at' => null,
            'price' => null
        ]);

        $this->clearMarketplaceCache();

        return true;
    }

    protected function clearMarketplaceCache(): void
    {
        try {
            // Use specific cache keys instead of tags
            Cache::forget('marketplace_listings');
            Cache::forget('marketplace_stats');
            Cache::forget('marketplace_listings_user_' . $this->user_id);
            Cache::forget('marketplace_sales_user_' . $this->user_id);
        } catch (\Exception $e) {
            report($e); // Log but don't throw
        }
    }

    // Boot Method
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($pack) {
            if ($pack->isDirty(['is_listed', 'price'])) {
                $pack->clearMarketplaceCache();
            }
        });
    }
}

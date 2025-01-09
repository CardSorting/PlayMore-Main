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
        'is_listed',
        'listed_at',
        'price'
    ];

    protected $casts = [
        'is_sealed' => 'boolean',
        'is_listed' => 'boolean',
        'listed_at' => 'datetime',
        'price' => 'integer'
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
               !$this->is_listed;
    }

    public function list(int $price): bool
    {
        if (!$this->canBeListed()) {
            return false;
        }

        $this->update([
            'is_listed' => true,
            'listed_at' => now(),
            'price' => $price
        ]);

        $this->clearMarketplaceCache();

        return true;
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
        Cache::tags(['marketplace_listings'])->flush();
        Cache::forget('marketplace_stats');
    }

    // Boot Method
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($pack) {
            if ($pack->isDirty(['is_listed', 'price'])) {
                Cache::tags(['marketplace_listings'])->flush();
                Cache::forget('marketplace_stats');
            }
        });
    }
}

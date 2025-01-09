<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pack extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'card_limit',
        'is_sealed',
        'price',
        'is_listed'
    ];

    protected $casts = [
        'is_sealed' => 'boolean',
        'card_limit' => 'integer',
        'is_listed' => 'boolean',
        'price' => 'integer',
        'listed_at' => 'datetime'
    ];

    public function listOnMarketplace(int $price): void
    {
        $this->update([
            'price' => $price,
            'is_listed' => true,
            'listed_at' => now()
        ]);
    }

    public function removeFromMarketplace(): void
    {
        $this->update([
            'is_listed' => false,
            'listed_at' => null
        ]);
    }

    public function scopeAvailableOnMarketplace($query)
    {
        return $query->where('is_listed', true)
                    ->where('is_sealed', true)
                    ->orderBy('listed_at', 'desc');
    }

    public function canBePurchased(): bool
    {
        return $this->is_listed && $this->is_sealed;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(GlobalCard::class);
    }
}

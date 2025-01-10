<?php

namespace App\Models;

use App\Contracts\Card;
use App\Models\Traits\HasCardMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends Model implements Card
{
    use HasCardMetadata;

    protected $fillable = [
        'user_id',
        'name',
        'prompt',
        'image_url',
        'type',
        'price',
        'is_available',
        'views_count',
        'metadata'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'views_count' => 'integer',
        'price' => 'integer',
        'metadata' => 'array'
    ];

    protected $attributes = [
        'metadata' => '[]'
    ];

    protected $hidden = [
        'mana_cost',
        'card_type',
        'abilities',
        'flavor_text',
        'power_toughness',
        'rarity'
    ];

    // Card interface implementation
    public function getName(): string
    {
        return $this->name;
    }

    public function getPrompt(): ?string
    {
        return $this->prompt;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price / 100, 2);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function printOrders(): HasMany
    {
        return $this->hasMany(PrintOrder::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByPopularity($query)
    {
        return $query->orderByDesc('views_count')
                    ->orderByDesc(
                        PrintOrder::selectRaw('count(*)')
                            ->whereColumn('gallery_id', 'galleries.id')
                            ->where('status', 'completed')
                    );
    }

    public function scopeByPrice($query, string $direction = 'asc')
    {
        return $query->orderBy('price', $direction);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function getPopularityScore(): float
    {
        // Calculate a score between 0 and 1 based on views and sales
        $viewsWeight = 0.4;
        $salesWeight = 0.6;

        // Get max values for normalization with minimum of 1
        $maxViews = max((int)(static::max('views_count') ?? 0), 1);
        $maxSales = max((int)(static::withCount(['printOrders' => function ($query) {
            $query->where('status', 'completed');
        }])->max('print_orders_count') ?? 0), 1);

        // Calculate normalized scores
        $viewsScore = (float)($this->views_count ?? 0) / $maxViews;
        $salesScore = (float)($this->printOrders()->where('status', 'completed')->count() ?? 0) / $maxSales;

        // Return weighted average
        return ($viewsScore * $viewsWeight) + ($salesScore * $salesWeight);
    }
}

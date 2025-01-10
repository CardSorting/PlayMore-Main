<?php

namespace App\Models;

use App\Contracts\Card as CardContract;
use App\DTOs\CardMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model implements CardContract
{
    protected $fillable = [
        'user_id',
        'gallery_id',
        'name',
        'mana_cost',
        'card_type',
        'flavor_text',
        'power_toughness',
        'rarity',
        'image_url'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Card interface implementation
    public function getName(): string
    {
        return $this->name;
    }

    public function getPrompt(): ?string
    {
        // Get prompt from associated gallery if it exists
        return $this->gallery?->prompt;
    }

    public function getType(): string
    {
        return 'card';
    }

    public function getCardMetadata(): CardMetadata
    {
        return new CardMetadata(
            mana_cost: $this->mana_cost,
            card_type: $this->card_type,
            abilities: $this->abilities->pluck('ability_text')->toArray(),
            flavor_text: $this->flavor_text,
            power_toughness: $this->power_toughness,
            rarity: $this->rarity,
            image_url: $this->image_url
        );
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function abilities(): HasMany
    {
        return $this->hasMany(CardAbility::class)->orderBy('order');
    }

    // Scopes
    public function scopeByPopularity($query)
    {
        return $query->orderByDesc(
            Gallery::selectRaw('views_count')
                ->whereColumn('galleries.id', 'cards.gallery_id')
                ->limit(1)
        );
    }

    public function scopeNewest($query)
    {
        return $query->where('created_at', '>=', now()->subDays(7));
    }

    // Helper methods
    public function getAbilitiesAsString(): string
    {
        return $this->abilities->pluck('ability_text')->implode("\n");
    }

    public function getRarityClasses(): string
    {
        return match(strtolower($this->rarity)) {
            'mythic rare' => 'mythic-rare-card mythic-holographic hover:scale-105',
            'rare' => 'rare-card rare-holographic hover:scale-104',
            'uncommon' => 'uncommon-card uncommon-holographic hover:scale-103',
            default => 'common-card hover:scale-102'
        };
    }

    public function getNormalizedRarity(): string
    {
        return strtolower(str_replace(' ', '-', $this->rarity));
    }
}

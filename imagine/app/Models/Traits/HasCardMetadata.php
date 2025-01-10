<?php

namespace App\Models\Traits;

use App\DTOs\CardMetadata;
use Illuminate\Support\Facades\Log;

trait HasCardMetadata
{
    public function getCardMetadata(): CardMetadata
    {
        $metadata = $this->metadata ?? [];
        
        Log::info(class_basename($this) . ' getCardMetadata', [
            'id' => $this->id,
            'metadata' => $metadata
        ]);

        return CardMetadata::fromArray($metadata);
    }

    public function getManaCountAttribute()
    {
        return $this->getCardMetadata()->mana_cost;
    }

    public function getCardTypeAttribute()
    {
        return $this->getCardMetadata()->card_type;
    }

    public function getAbilitiesAttribute()
    {
        return $this->getCardMetadata()->abilities;
    }

    public function getRarityAttribute()
    {
        return $this->getCardMetadata()->rarity;
    }

    public function getPowerToughnessAttribute()
    {
        return $this->getCardMetadata()->power_toughness;
    }

    public function getCardImageUrlAttribute()
    {
        return $this->getCardMetadata()->image_url ?? $this->attributes['image_url'] ?? null;
    }

    protected function setCardMetadata(CardMetadata $metadata): void
    {
        $this->metadata = array_merge($this->metadata ?? [], $metadata->toArray());
    }
}

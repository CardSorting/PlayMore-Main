<?php

namespace App\Models\Traits;

use App\DTOs\CardMetadata;
use Illuminate\Support\Facades\Log;

trait HasCardMetadata
{
    public function getCardMetadata(): CardMetadata
    {
        $rawMetadata = $this->getRawOriginal('metadata');
        $metadata = $this->metadata ?? [];
        
        Log::info(class_basename($this) . ' getCardMetadata', [
            'id' => $this->id,
            'raw_metadata' => $rawMetadata,
            'decoded_metadata' => $metadata,
            'is_array' => is_array($metadata),
            'is_json' => is_string($rawMetadata) && $this->isJson($rawMetadata)
        ]);

        if (empty($metadata)) {
            Log::warning(class_basename($this) . ' empty metadata', [
                'id' => $this->id,
                'raw_metadata' => $rawMetadata,
                'attributes' => $this->getAttributes()
            ]);
        }

        return CardMetadata::fromArray($metadata);
    }

    private function isJson($string): bool 
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
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

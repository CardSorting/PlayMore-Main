<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\GlobalCard;
use App\DTOs\CardMetadata;

class CardDataMapper
{
    public function galleryToGlobalCard(Gallery $gallery, int $packId): GlobalCard
    {
        $metadata = $gallery->getCardMetadata();
        
        return GlobalCard::create([
            'pack_id' => $packId,
            'original_user_id' => $gallery->user_id,
            'type' => $gallery->type,
            'name' => $gallery->name,
            'image_url' => $metadata->image_url ?? $gallery->image_url,
            'prompt' => $gallery->prompt,
            'aspect_ratio' => $gallery->aspect_ratio,
            'process_mode' => $gallery->process_mode,
            'task_id' => $gallery->task_id,
            'metadata' => $metadata->toArray(),
            'mana_cost' => $metadata->mana_cost,
            'card_type' => $metadata->card_type,
            'abilities' => $metadata->abilities,
            'flavor_text' => $metadata->flavor_text,
            'power_toughness' => $metadata->power_toughness,
            'rarity' => $metadata->rarity
        ]);
    }

    public function globalCardToMetadata(GlobalCard $card): CardMetadata
    {
        return new CardMetadata(
            mana_cost: $card->mana_cost,
            card_type: $card->card_type,
            abilities: $card->abilities,
            flavor_text: $card->flavor_text,
            power_toughness: $card->power_toughness,
            rarity: $card->rarity,
            image_url: $card->image_url
        );
    }

    public function galleryToMetadata(Gallery $gallery): CardMetadata
    {
        return $gallery->getCardMetadata();
    }
}

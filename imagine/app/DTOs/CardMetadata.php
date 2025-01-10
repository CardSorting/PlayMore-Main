<?php

namespace App\DTOs;

class CardMetadata
{
    public function __construct(
        public readonly ?string $mana_cost,
        public readonly ?string $card_type,
        public readonly ?array $abilities,
        public readonly ?string $flavor_text,
        public readonly ?string $power_toughness,
        public readonly ?string $rarity,
        public readonly ?string $image_url
    ) {}

    public static function fromArray(array $data): self
    {
        // Convert abilities string to array if needed
        $abilities = isset($data['abilities']) 
            ? (is_string($data['abilities']) 
                ? array_map('trim', explode("\n", $data['abilities'])) 
                : $data['abilities'])
            : null;

        return new self(
            mana_cost: $data['mana_cost'] ?? null,
            card_type: $data['card_type'] ?? null,
            abilities: $abilities,
            flavor_text: $data['flavor_text'] ?? null,
            power_toughness: $data['power_toughness'] ?? null,
            rarity: $data['rarity'] ?? null,
            image_url: $data['image_url'] ?? null
        );
    }

    public function getAbilitiesAsString(): string
    {
        return $this->abilities ? implode("\n", $this->abilities) : '';
    }

    public function toArray(): array
    {
        return [
            'mana_cost' => $this->mana_cost,
            'card_type' => $this->card_type,
            'abilities' => $this->abilities,
            'flavor_text' => $this->flavor_text,
            'power_toughness' => $this->power_toughness,
            'rarity' => $this->rarity,
            'image_url' => $this->image_url,
        ];
    }
}

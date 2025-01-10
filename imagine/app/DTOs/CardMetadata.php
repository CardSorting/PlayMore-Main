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
        \Log::info('Creating CardMetadata from array', [
            'input_data' => $data
        ]);

        try {
            // Convert abilities string to array if needed
            $abilities = isset($data['abilities']) 
                ? (is_string($data['abilities']) 
                    ? array_map('trim', explode("\n", $data['abilities'])) 
                    : $data['abilities'])
                : [];

            // Ensure abilities is always an array
            if (!is_array($abilities)) {
                \Log::warning('Invalid abilities format', [
                    'abilities' => $abilities
                ]);
                $abilities = [];
            }

            $metadata = new self(
                mana_cost: $data['mana_cost'] ?? '',
                card_type: $data['card_type'] ?? 'Unknown Type',
                abilities: $abilities,
                flavor_text: $data['flavor_text'] ?? '',
                power_toughness: $data['power_toughness'] ?? null,
                rarity: $data['rarity'] ?? 'Common',
                image_url: $data['image_url'] ?? null
            );

            \Log::info('Created CardMetadata', [
                'result' => $metadata->toArray()
            ]);

            return $metadata;
        } catch (\Exception $e) {
            \Log::error('Error creating CardMetadata', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
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

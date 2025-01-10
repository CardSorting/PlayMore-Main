<?php

namespace App\ViewModels;

use App\Contracts\Card;
use App\DTOs\CardMetadata;

class CardViewModel
{
    private CardMetadata $metadata;
    private string $name;
    private string $author;

    public function __construct(
        CardMetadata $metadata,
        string $name,
        string $author = 'Unknown Author'
    ) {
        $this->metadata = $metadata;
        $this->name = $name;
        $this->author = $author;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            CardMetadata::fromArray([
                'mana_cost' => $data['mana_cost'] ?? null,
                'card_type' => $data['card_type'] ?? 'Unknown Type',
                'abilities' => $data['abilities'] ?? 'No abilities',
                'flavor_text' => $data['flavor_text'] ?? '',
                'power_toughness' => $data['power_toughness'] ?? null,
                'rarity' => $data['rarity'] ?? 'Common',
                'image_url' => $data['image_url'] ?? '/static/images/placeholder.png'
            ]),
            $data['name'] ?? 'Unnamed Card',
            $data['author'] ?? 'Unknown Author'
        );
    }

    public static function fromCard(Card $card, ?string $author = null): self
    {
        try {
            $metadata = $card->getCardMetadata();
            
            \Log::info('Creating CardViewModel from card', [
                'card_id' => $card->id ?? 'unknown',
                'card_name' => $card->getName(),
                'metadata' => $metadata->toArray(),
                'author' => $author
            ]);

            return new self(
                $metadata,
                $card->getName(),
                $author ?? 'Unknown Author'
            );
        } catch (\Exception $e) {
            \Log::error('Error creating CardViewModel', [
                'card_id' => $card->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Create a default metadata object
            $defaultMetadata = new CardMetadata(
                mana_cost: '',
                card_type: 'Unknown Type',
                abilities: [],
                flavor_text: '',
                power_toughness: null,
                rarity: 'Common',
                image_url: $card->image_url ?? '/static/images/placeholder.png'
            );

            return new self(
                $defaultMetadata,
                $card->getName(),
                $author ?? 'Unknown Author'
            );
        }
    }

    public function toArray(): array
    {
        $metadata = $this->metadata->toArray();
        
        $result = [
            'name' => $this->name,
            'author' => $this->author,
            'mana_cost' => $metadata['mana_cost'] ?? '',
            'card_type' => $metadata['card_type'] ?? 'Unknown Type',
            'abilities' => $this->formatAbilities($metadata['abilities'] ?? []),
            'abilities_array' => $metadata['abilities'] ?? [],
            'abilities_text' => $this->metadata->getAbilitiesAsString(),
            'flavor_text' => $metadata['flavor_text'] ?? '',
            'power_toughness' => $metadata['power_toughness'] ?? null,
            'rarity' => $metadata['rarity'] ?? 'Common',
            'image_url' => $metadata['image_url'] ?? '/static/images/placeholder.png'
        ];

        \Log::info('CardViewModel converted to array', [
            'name' => $result['name'],
            'metadata' => $metadata,
            'result' => $result
        ]);

        return $result;
    }

    private function formatAbilities(?array $abilities): string
    {
        if (!$abilities || empty($abilities)) {
            return 'No abilities';
        }

        return implode("\n", array_map(
            fn($ability) => trim($ability),
            $abilities
        ));
    }

    public function getRarityClasses(): string
    {
        $rarity = strtolower($this->metadata->rarity ?? 'common');
        
        return match($rarity) {
            'mythic rare' => 'mythic-rare-card mythic-holographic hover:scale-105',
            'rare' => 'rare-card rare-holographic hover:scale-104',
            'uncommon' => 'uncommon-card uncommon-holographic hover:scale-103',
            default => 'common-card hover:scale-102'
        };
    }

    public function getNormalizedRarity(): string
    {
        return strtolower(str_replace(' ', '-', $this->metadata->rarity ?? 'common'));
    }
}

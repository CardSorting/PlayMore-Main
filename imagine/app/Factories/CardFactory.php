<?php

namespace App\Factories;

use App\DTOs\CardMetadata;
use App\Models\Gallery;
use App\Services\CardDataMapper;
use App\Repositories\CardRepository;

class CardFactory
{
    private array $rarityWeights = [
        'Common' => 50,
        'Uncommon' => 30,
        'Rare' => 15,
        'Mythic Rare' => 5
    ];

    public function __construct(
        private CardRepository $cardRepository,
        private CardDataMapper $cardDataMapper
    ) {}

    /**
     * Create a new card from an image
     */
    public function createFromImage(Gallery $image, array $data): Gallery
    {
        // Validate image type
        if ($image->type !== 'image') {
            throw new \InvalidArgumentException('Gallery item must be of type "image"');
        }

        // Check for existing card
        if ($this->cardRepository->cardExistsWithImage($image->image_url)) {
            throw new \RuntimeException('A card already exists for this image');
        }

        // Create metadata with proper null handling
        $cardMetadata = new CardMetadata(
            mana_cost: $this->formatManaCount($data['mana_cost'] ?? ''),
            card_type: $data['card_type'] ?? 'Unknown Type',
            abilities: $data['abilities'] ?? null,
            flavor_text: $data['flavor_text'] ?? null,
            power_toughness: $this->formatPowerToughness($data['power_toughness'] ?? null),
            rarity: $this->generateRarity(),
            image_url: $image->image_url
        );

        // Additional metadata for tracking
        $additionalMetadata = [
            'original_image_id' => $image->id,
            'created_from' => 'image',
            'created_at' => now()->toISOString(),
            'original_metadata' => $image->metadata,
            'original_author' => [
                'id' => $image->user->id,
                'name' => $image->user->name
            ]
        ];

        // Create the card using repository
        return $this->cardRepository->createGalleryCard(
            userId: $image->user_id,
            name: $data['name'],
            imageUrl: $image->image_url,
            metadata: $cardMetadata,
            additionalMetadata: $additionalMetadata
        );
    }

    /**
     * Create a global card from a gallery card
     */
    public function createGlobalFromGallery(Gallery $gallery, int $packId): GlobalCard
    {
        // Validate gallery type
        if ($gallery->type !== 'card') {
            throw new \InvalidArgumentException('Gallery item must be of type "card"');
        }

        return $this->cardRepository->createGlobalCard(
            packId: $packId,
            originalUserId: $gallery->user_id,
            name: $gallery->name,
            imageUrl: $gallery->getCardImageUrlAttribute(),
            metadata: $gallery->getCardMetadata(),
            additionalMetadata: [
                'original_gallery_id' => $gallery->id,
                'created_at' => now()->toISOString()
            ]
        );
    }

    /**
     * Format mana cost to consistent format
     */
    private function formatManaCount(?string $manaCost): string
    {
        if (!$manaCost) {
            return '';
        }

        // Remove any whitespace and join with commas
        return implode(',', array_filter(str_split(trim($manaCost))));
    }

    /**
     * Format power/toughness to consistent format
     */
    private function formatPowerToughness(?string $powerToughness): ?string
    {
        if (!$powerToughness) {
            return null;
        }

        // Remove any whitespace and ensure format is "N/N"
        $parts = array_map('trim', explode('/', $powerToughness));
        if (count($parts) !== 2) {
            return null;
        }

        return implode('/', $parts);
    }

    /**
     * Generate rarity based on weighted probabilities
     */
    private function generateRarity(): string
    {
        $total = array_sum($this->rarityWeights);
        $roll = rand(1, $total);
        
        foreach ($this->rarityWeights as $rarity => $weight) {
            if ($roll <= $weight) {
                return $rarity;
            }
            $roll -= $weight;
        }

        return 'Common'; // Fallback
    }
}

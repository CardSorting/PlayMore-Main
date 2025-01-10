<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\GlobalCard;
use App\Contracts\Card;
use App\DTOs\CardMetadata;
use App\Factories\CardFactory;
use App\Repositories\CardRepository;
use App\ViewModels\CardViewModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CardService
{
    public function __construct(
        private CardRepository $cardRepository,
        private CardFactory $cardFactory,
        private CardDataMapper $cardDataMapper
    ) {}

    /**
     * Create a new card from an image with transaction safety
     */
    public function createCardFromImage(Gallery $image, array $data): Card
    {
        return DB::transaction(function () use ($image, $data) {
            try {
                // Create metadata
                $metadata = new CardMetadata(
                    mana_cost: $data['mana_cost'] ?? '',
                    card_type: $data['card_type'] ?? 'Unknown Type',
                    abilities: $this->formatAbilities($data['abilities'] ?? ''),
                    flavor_text: $data['flavor_text'] ?? '',
                    power_toughness: $this->formatPowerToughness($data['power_toughness'] ?? null),
                    rarity: $data['rarity'] ?? 'Common',
                    image_url: $image->image_url
                );

                // Create card using repository
                $card = $this->cardRepository->createCard(
                    userId: $image->user_id,
                    name: $data['name'],
                    imageUrl: $image->image_url,
                    metadata: $metadata,
                    galleryId: $image->id
                );

                Log::info('Card created successfully', [
                    'card_id' => $card->id,
                    'image_id' => $image->id,
                    'metadata' => $metadata->toArray()
                ]);

                return $card;
            } catch (\Exception $e) {
                Log::error('Failed to create card', [
                    'image_id' => $image->id,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Convert a card to a global card for packs
     */
    public function convertToGlobalCard(Card $card, int $packId): GlobalCard
    {
        return DB::transaction(function () use ($card, $packId) {
            try {
                $globalCard = $this->cardRepository->createGlobalCard(
                    packId: $packId,
                    originalUserId: $card->user_id,
                    name: $card->name,
                    imageUrl: $card->image_url,
                    metadata: $card->getCardMetadata()
                );

                Log::info('Card converted to global card', [
                    'card_id' => $card->id,
                    'global_card_id' => $globalCard->id,
                    'pack_id' => $packId
                ]);

                return $globalCard;
            } catch (\Exception $e) {
                Log::error('Failed to convert card to global card', [
                    'card_id' => $card->id,
                    'pack_id' => $packId,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Get user's cards with view models
     */
    public function getUserCards(int $userId, array $filters = []): Collection
    {
        $cards = $this->cardRepository->getUserCards($userId, $filters);

        Log::info('Retrieved user cards', [
            'user_id' => $userId,
            'count' => $cards->count(),
            'first_card' => $cards->first() ? [
                'id' => $cards->first()->id,
                'name' => $cards->first()->name,
                'abilities_count' => $cards->first()->abilities->count()
            ] : null
        ]);

        return $cards;
    }

    /**
     * Get pack's global cards with view models
     */
    public function getPackCards(int $packId): Collection
    {
        return $this->cardRepository->getPackGlobalCards($packId);
    }

    /**
     * Find a card by ID and type with null safety
     */
    public function findCard(int $id, string $type = 'card'): ?CardContract
    {
        try {
            $card = $this->cardRepository->findCard($id, $type);

            if (!$card) {
                Log::warning('Card not found', [
                    'id' => $id,
                    'type' => $type
                ]);
                return null;
            }

            return $card;
        } catch (\Exception $e) {
            Log::error('Error finding card', [
                'id' => $id,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Check if a card exists for an image URL
     */
    public function cardExistsForImage(string $imageUrl): bool
    {
        try {
            return $this->cardRepository->cardExistsWithImage($imageUrl);
        } catch (\Exception $e) {
            Log::error('Error checking card existence', [
                'image_url' => $imageUrl,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Format abilities into an array
     */
    private function formatAbilities(?string $abilities): array
    {
        if (!$abilities) {
            return [];
        }

        return array_values(array_filter(
            array_map('trim', explode("\n", $abilities))
        ));
    }

    /**
     * Format power/toughness to consistent format
     */
    private function formatPowerToughness(?string $powerToughness): ?string
    {
        if (!$powerToughness) {
            return null;
        }

        $parts = array_map('trim', explode('/', $powerToughness));
        return count($parts) === 2 ? implode('/', $parts) : null;
    }
}

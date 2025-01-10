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
    public function createCardFromImage(Gallery $image, array $data): Gallery
    {
        return DB::transaction(function () use ($image, $data) {
            // Validate and create card
            try {
                $card = $this->cardFactory->createFromImage($image, $data);

                Log::info('Card created successfully', [
                    'card_id' => $card->id,
                    'image_id' => $image->id,
                    'metadata' => $card->getCardMetadata()->toArray()
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
     * Convert a gallery card to a global card for packs
     */
    public function convertToGlobalCard(Gallery $gallery, int $packId): GlobalCard
    {
        return DB::transaction(function () use ($gallery, $packId) {
            try {
                $globalCard = $this->cardFactory->createGlobalFromGallery($gallery, $packId);

                Log::info('Gallery card converted to global card', [
                    'gallery_id' => $gallery->id,
                    'global_card_id' => $globalCard->id,
                    'pack_id' => $packId
                ]);

                return $globalCard;
            } catch (\Exception $e) {
                Log::error('Failed to convert gallery card to global card', [
                    'gallery_id' => $gallery->id,
                    'pack_id' => $packId,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Get user's gallery cards with view models
     */
    public function getUserCards(int $userId, array $filters = []): Collection
    {
        $cards = $this->cardRepository->getUserGalleryCards($userId, $filters);

        return $cards->map(function (Gallery $card) {
            return CardViewModel::fromCard(
                $card,
                optional($card->user)->name
            );
        });
    }

    /**
     * Get pack's global cards with view models
     */
    public function getPackCards(int $packId): Collection
    {
        $cards = $this->cardRepository->getPackGlobalCards($packId);

        return $cards->map(function (GlobalCard $card) {
            return CardViewModel::fromCard(
                $card,
                optional($card->originalUser)->name
            );
        });
    }

    /**
     * Find a card by ID and type with null safety
     */
    public function findCard(int $id, string $type = 'gallery'): ?Card
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
     * Get a view model for a card
     */
    public function getCardViewModel(Card $card, ?string $author = null): CardViewModel
    {
        return CardViewModel::fromCard($card, $author);
    }

    /**
     * Update card metadata with validation
     */
    public function updateCardMetadata(Card $card, CardMetadata $metadata): bool
    {
        try {
            if ($card instanceof Gallery) {
                return DB::transaction(function () use ($card, $metadata) {
                    $card->metadata = array_merge(
                        $card->metadata ?? [],
                        $metadata->toArray()
                    );
                    return $card->save();
                });
            }

            Log::warning('Attempted to update metadata for non-gallery card', [
                'card_type' => get_class($card),
                'card_id' => $card->id
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Failed to update card metadata', [
                'card_id' => $card->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

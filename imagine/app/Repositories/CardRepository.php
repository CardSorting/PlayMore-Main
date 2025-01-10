<?php

namespace App\Repositories;

use App\Models\Card;
use App\Models\CardAbility;
use App\Models\GlobalCard;
use App\Contracts\Card as CardContract;
use App\DTOs\CardMetadata;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CardRepository
{
    /**
     * Get a card by its ID and type
     */
    public function findCard(int $id, string $type = 'card'): ?CardContract
    {
        return match($type) {
            'card' => Card::with(['abilities', 'user'])->find($id),
            'global' => GlobalCard::find($id),
            default => null
        };
    }

    /**
     * Check if a card exists with the given image URL
     */
    public function cardExistsWithImage(string $imageUrl): bool
    {
        return Card::where('image_url', $imageUrl)->exists() ||
               GlobalCard::where('image_url', $imageUrl)->exists();
    }

    /**
     * Get user's cards with eager loading
     */
    public function getUserCards(int $userId, array $filters = []): Collection
    {
        $query = Card::with(['abilities', 'user', 'gallery' => function($query) {
            $query->select('id', 'prompt', 'views_count');
        }])
            ->where('user_id', $userId);

        if (isset($filters['newest']) && $filters['newest']) {
            $query->newest();
        }

        if (isset($filters['sort'])) {
            match($filters['sort']) {
                'popularity' => $query->byPopularity(),
                default => $query->orderBy('created_at', 'desc')
            };
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $cards = $query->get();

        \Log::info('Retrieved user cards from repository', [
            'user_id' => $userId,
            'count' => $cards->count(),
            'first_card' => $cards->first() ? [
                'id' => $cards->first()->id,
                'name' => $cards->first()->name,
                'abilities' => $cards->first()->abilities->pluck('ability_text'),
                'user' => [
                    'id' => $cards->first()->user->id,
                    'name' => $cards->first()->user->name
                ]
            ] : null
        ]);

        return $cards;
    }

    /**
     * Create a new card with abilities
     */
    public function createCard(
        int $userId,
        string $name,
        string $imageUrl,
        CardMetadata $metadata,
        ?int $galleryId = null
    ): Card {
        return DB::transaction(function () use ($userId, $name, $imageUrl, $metadata, $galleryId) {
            // Create the card
            $card = Card::create([
                'user_id' => $userId,
                'gallery_id' => $galleryId,
                'name' => $name,
                'mana_cost' => $metadata->mana_cost ?? '',
                'card_type' => $metadata->card_type ?? 'Unknown Type',
                'flavor_text' => $metadata->flavor_text ?? '',
                'power_toughness' => $metadata->power_toughness,
                'rarity' => $metadata->rarity ?? 'Common',
                'image_url' => $imageUrl
            ]);

            // Create abilities
            if (!empty($metadata->abilities)) {
                foreach ($metadata->abilities as $index => $ability) {
                    CardAbility::create([
                        'card_id' => $card->id,
                        'ability_text' => $ability,
                        'order' => $index
                    ]);
                }
            }

            return $card->load(['abilities', 'user']);
        });
    }

    /**
     * Create a new global card
     */
    public function createGlobalCard(
        int $packId,
        int $originalUserId,
        string $name,
        string $imageUrl,
        CardMetadata $metadata
    ): GlobalCard {
        return GlobalCard::create([
            'pack_id' => $packId,
            'original_user_id' => $originalUserId,
            'name' => $name,
            'image_url' => $imageUrl,
            'metadata' => $metadata->toArray()
        ]);
    }

    /**
     * Get existing card by image URL
     */
    public function findByImageUrl(string $imageUrl): ?CardContract
    {
        return Card::where('image_url', $imageUrl)->first() ??
               GlobalCard::where('image_url', $imageUrl)->first();
    }
}

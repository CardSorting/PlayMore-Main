<?php

namespace App\Repositories;

use App\Models\Gallery;
use App\Models\GlobalCard;
use App\Contracts\Card;
use App\DTOs\CardMetadata;
use Illuminate\Support\Collection;

class CardRepository
{
    /**
     * Get a card by its ID and type
     */
    public function findCard(int $id, string $type = 'gallery'): ?Card
    {
        return match($type) {
            'gallery' => Gallery::where('type', 'card')->find($id),
            'global' => GlobalCard::find($id),
            default => null
        };
    }

    /**
     * Check if a card exists with the given image URL
     */
    public function cardExistsWithImage(string $imageUrl): bool
    {
        return Gallery::where('type', 'card')
            ->where('image_url', $imageUrl)
            ->exists() ||
            GlobalCard::where('image_url', $imageUrl)
            ->exists();
    }

    /**
     * Get user's gallery cards with pagination
     */
    public function getUserGalleryCards(int $userId, array $filters = []): Collection
    {
        $query = Gallery::where('type', 'card')
            ->where('user_id', $userId)
            ->with('user');

        if (isset($filters['newest']) && $filters['newest']) {
            $query->where('created_at', '>=', now()->subDays(7));
        }

        if (isset($filters['sort'])) {
            match($filters['sort']) {
                'popularity' => $query->orderByDesc('views_count'),
                'price' => $query->orderBy('price'),
                'price_desc' => $query->orderBy('price', 'desc'),
                default => $query->orderBy('created_at', 'desc')
            };
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->get();
    }

    /**
     * Get global cards for a pack
     */
    public function getPackGlobalCards(int $packId): Collection
    {
        return GlobalCard::where('pack_id', $packId)
            ->with('originalUser')
            ->get();
    }

    /**
     * Create a new gallery card
     */
    public function createGalleryCard(
        int $userId,
        string $name,
        string $imageUrl,
        CardMetadata $metadata,
        array $additionalMetadata = []
    ): Gallery {
        return Gallery::create([
            'user_id' => $userId,
            'type' => 'card',
            'name' => $name,
            'image_url' => $imageUrl,
            'metadata' => array_merge(
                $metadata->toArray(),
                $additionalMetadata
            )
        ]);
    }

    /**
     * Create a new global card
     */
    public function createGlobalCard(
        int $packId,
        int $originalUserId,
        string $name,
        string $imageUrl,
        CardMetadata $metadata,
        array $additionalMetadata = []
    ): GlobalCard {
        return GlobalCard::create([
            'pack_id' => $packId,
            'original_user_id' => $originalUserId,
            'type' => 'card',
            'name' => $name,
            'image_url' => $imageUrl,
            'metadata' => array_merge(
                $metadata->toArray(),
                $additionalMetadata
            )
        ]);
    }

    /**
     * Get existing card by image URL
     */
    public function findByImageUrl(string $imageUrl): ?Card
    {
        return Gallery::where('type', 'card')
            ->where('image_url', $imageUrl)
            ->first() ??
            GlobalCard::where('image_url', $imageUrl)
            ->first();
    }
}

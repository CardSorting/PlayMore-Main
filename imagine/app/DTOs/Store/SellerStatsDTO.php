<?php

namespace App\DTOs\Store;

class SellerStatsDTO
{
    public function __construct(
        public readonly int $artworks,
        public readonly int $sales,
        public readonly float $rating,
        public readonly int $totalRatings,
        public readonly string $joinedDate
    ) {}

    public static function fromUser($user): self
    {
        return new self(
            artworks: $user->galleries()->count(),
            sales: $user->printOrders()->where('status', 'completed')->count(),
            rating: $user->average_rating,
            totalRatings: $user->total_ratings,
            joinedDate: $user->created_at->format('F Y')
        );
    }
}

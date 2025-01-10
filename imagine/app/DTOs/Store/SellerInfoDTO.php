<?php

namespace App\DTOs\Store;

class SellerInfoDTO
{
    public function __construct(
        public readonly bool $isOnline,
        public readonly string $responseTime,
        public readonly string $shipsFrom,
        public readonly array $shipsTo,
        public readonly ?string $website,
        public readonly ?string $instagram,
        public readonly ?string $twitter
    ) {}

    public static function fromUser($user): self
    {
        $responseTime = match (true) {
            $user->avg_response_minutes <= 60 => 'Usually within an hour',
            $user->avg_response_minutes <= 180 => 'Usually within 3 hours',
            $user->avg_response_minutes <= 1440 => 'Usually within a day',
            default => 'Usually within 2-3 days'
        };

        return new self(
            isOnline: $user->isOnline(),
            responseTime: $responseTime,
            shipsFrom: $user->country ?? 'United States',
            shipsTo: $user->shipping_countries ?? ['United States'],
            website: $user->website,
            instagram: $user->social_instagram,
            twitter: $user->social_twitter
        );
    }
}

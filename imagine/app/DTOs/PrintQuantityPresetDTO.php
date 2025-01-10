<?php

namespace App\DTOs;

class PrintQuantityPresetDTO
{
    public function __construct(
        public readonly int $amount,
        public readonly string $label,
        public readonly string $description,
        public readonly ?string $savings,
        public readonly bool $popular,
        public readonly ?int $originalPrice = null,
        public readonly ?int $discountedPrice = null,
        public readonly ?int $savingsAmount = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            amount: $data['amount'],
            label: $data['label'],
            description: $data['description'],
            savings: $data['savings'],
            popular: $data['popular'] ?? false,
            originalPrice: $data['originalPrice'] ?? null,
            discountedPrice: $data['discountedPrice'] ?? null,
            savingsAmount: $data['savingsAmount'] ?? null
        );
    }
}

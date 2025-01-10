<?php

namespace App\Services;

use App\DTOs\PrintQuantityPresetDTO;
use App\Models\PrintOrder;
use Illuminate\Support\Collection;

class PrintQuantityService
{
    private const QUANTITY_PRESETS = [
        [
            'amount' => 1,
            'label' => 'Single Print',
            'description' => 'Perfect for a statement piece',
            'savings' => null,
            'popular' => false
        ],
        [
            'amount' => 3,
            'label' => 'Triptych Set',
            'description' => 'Create a classic three-piece display',
            'savings' => '10% off',
            'popular' => true
        ],
        [
            'amount' => 5,
            'label' => 'Gallery Wall',
            'description' => 'Ideal for a small gallery arrangement',
            'savings' => '15% off',
            'popular' => true
        ],
        [
            'amount' => 20,
            'label' => 'Studio Pack',
            'description' => 'Perfect for a large gallery display',
            'savings' => '25% off',
            'popular' => true
        ],
        [
            'amount' => 50,
            'label' => 'Boutique Collection',
            'description' => 'Ideal for small retail or galleries',
            'savings' => '35% off',
            'popular' => false
        ],
        [
            'amount' => 100,
            'label' => 'Wholesale Pack',
            'description' => 'For retail stores and resellers',
            'savings' => '40% off',
            'popular' => false
        ],
        [
            'amount' => 250,
            'label' => 'Bulk Order',
            'description' => 'Maximum savings for large orders',
            'savings' => '45% off',
            'popular' => false
        ]
    ];

    public function getPresets(PrintOrder $order): Collection
    {
        return collect(self::QUANTITY_PRESETS)->map(function ($preset) use ($order) {
            $originalPrice = $order->unit_price * $preset['amount'];
            $discountedPrice = $originalPrice;
            $savingsAmount = 0;

            if ($preset['savings']) {
                $discount = (int) rtrim($preset['savings'], '% off');
                $discountedPrice = (int) ($originalPrice * (100 - $discount) / 100);
                $savingsAmount = $originalPrice - $discountedPrice;
            }

            return PrintQuantityPresetDTO::fromArray([
                ...$preset,
                'originalPrice' => $originalPrice,
                'discountedPrice' => $discountedPrice,
                'savingsAmount' => $savingsAmount
            ]);
        });
    }

    public function calculatePrice(PrintOrder $order, int $quantity): array
    {
        $preset = collect(self::QUANTITY_PRESETS)
            ->firstWhere('amount', $quantity);

        if (!$preset) {
            return [
                'total' => $order->unit_price * $quantity,
                'savings' => null,
                'originalPrice' => null
            ];
        }

        $originalPrice = $order->unit_price * $quantity;
        
        if (!$preset['savings']) {
            return [
                'total' => $originalPrice,
                'savings' => null,
                'originalPrice' => null
            ];
        }

        $discount = (int) rtrim($preset['savings'], '% off');
        $discountedPrice = (int) ($originalPrice * (100 - $discount) / 100);

        return [
            'total' => $discountedPrice,
            'savings' => $preset['savings'],
            'originalPrice' => $originalPrice
        ];
    }

    public function getMaxQuantity(): int
    {
        return 250; // Matches our highest preset quantity
    }

    public function validateQuantity(int $quantity): bool
    {
        return $quantity >= 1 && $quantity <= $this->getMaxQuantity();
    }
}

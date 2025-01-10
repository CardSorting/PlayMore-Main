<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrintOrder extends Model
{
    use HasFactory;

    // Print sizes and their prices
    public const SIZES = [
        '2.5x3.5' => [
            'name' => '2.5" x 3.5"',
            'price' => 4.99
        ],
        '4x6' => [
            'name' => '4" x 6"',
            'price' => 7.99
        ],
        '8x10' => [
            'name' => '8" x 10"',
            'price' => 14.99
        ],
        '11x17' => [
            'name' => '11" x 17"',
            'price' => 24.99
        ],
        '18x24' => [
            'name' => '18" x 24"',
            'price' => 39.99
        ],
        '24x36' => [
            'name' => '24" x 36"',
            'price' => 59.99
        ]
    ];

    protected $fillable = [
        'user_id',
        'gallery_id',
        'size',
        'price',
        'status',
        'shipping_name',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'shipping_country',
        'stripe_payment_intent_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public static function getSizePrice(string $size): float
    {
        return self::SIZES[$size]['price'] ?? 0.00;
    }

    public static function getSizeName(string $size): string
    {
        return self::SIZES[$size]['name'] ?? $size;
    }

    public function getFormattedAddressAttribute(): string
    {
        return implode(', ', [
            $this->shipping_address,
            $this->shipping_city,
            $this->shipping_state,
            $this->shipping_zip,
            $this->shipping_country
        ]);
    }
}

<?php

namespace App\DTOs;

use App\Models\Gallery;
use Illuminate\Support\Collection;

class PrintOrderData
{
    public readonly string $size;
    public readonly float $price;
    public readonly Gallery $gallery;
    public readonly string $shipping_name;
    public readonly string $shipping_address;
    public readonly string $shipping_city;
    public readonly string $shipping_state;
    public readonly string $shipping_zip;
    public readonly string $shipping_country;
    public readonly ?string $notes;

    public function __construct(
        string $size,
        float $price,
        Gallery $gallery,
        string $shipping_name,
        string $shipping_address,
        string $shipping_city,
        string $shipping_state,
        string $shipping_zip,
        string $shipping_country,
        ?string $notes = null
    ) {
        $this->size = $size;
        $this->price = $price;
        $this->gallery = $gallery;
        $this->shipping_name = $shipping_name;
        $this->shipping_address = $shipping_address;
        $this->shipping_city = $shipping_city;
        $this->shipping_state = $shipping_state;
        $this->shipping_zip = $shipping_zip;
        $this->shipping_country = $shipping_country;
        $this->notes = $notes;
    }

    public static function fromRequest(array $data, Gallery $gallery): self
    {
        return new self(
            size: $data['size'],
            price: $data['price'],
            gallery: $gallery,
            shipping_name: $data['shipping_name'],
            shipping_address: $data['shipping_address'],
            shipping_city: $data['shipping_city'],
            shipping_state: $data['shipping_state'],
            shipping_zip: $data['shipping_zip'],
            shipping_country: $data['shipping_country'],
            notes: $data['notes'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'size' => $this->size,
            'price' => $this->price,
            'gallery_id' => $this->gallery->id,
            'shipping_name' => $this->shipping_name,
            'shipping_address' => $this->shipping_address,
            'shipping_city' => $this->shipping_city,
            'shipping_state' => $this->shipping_state,
            'shipping_zip' => $this->shipping_zip,
            'shipping_country' => $this->shipping_country,
            'notes' => $this->notes,
        ];
    }

    public function getFormattedAddress(): string
    {
        return collect([
            $this->shipping_address,
            $this->shipping_city,
            $this->shipping_state,
            $this->shipping_zip,
            $this->shipping_country
        ])->filter()->join(', ');
    }
}
<?php

namespace App\DTOs\Store;

class StoreFiltersDTO
{
    public function __construct(
        public readonly string $tab,
        public readonly string $sort,
        public readonly ?string $search,
        public readonly ?float $priceMin,
        public readonly ?float $priceMax,
        public readonly object $priceRanges
    ) {}

    public static function fromRequest($request, $priceRanges): self
    {
        return new self(
            tab: $request->get('tab', 'prints'),
            sort: $request->get('sort', 'newest'),
            search: $request->get('search'),
            priceMin: $request->get('price_min'),
            priceMax: $request->get('price_max'),
            priceRanges: $priceRanges
        );
    }

    public function getSortOptions(): array
    {
        return [
            'newest' => 'Newest',
            'popular' => 'Most Popular',
            'price_low' => 'Price: Low to High',
            'price_high' => 'Price: High to Low'
        ];
    }
}

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shipping Zones
    |--------------------------------------------------------------------------
    |
    | Define shipping zones and their associated countries. Each country is
    | mapped to a zone type that determines shipping rates and delivery times.
    |
    */
    'shipping_zones' => [
        // North America
        'US' => 'domestic',
        'CA' => 'canada',
        'MX' => 'mexico',

        // Europe
        'GB' => 'europe',
        'DE' => 'europe',
        'FR' => 'europe',
        'IT' => 'europe',
        'ES' => 'europe',
        'NL' => 'europe',
        'BE' => 'europe',
        'CH' => 'europe',
        'AT' => 'europe',
        'SE' => 'europe',
        'NO' => 'europe',
        'DK' => 'europe',
        'FI' => 'europe',
        'IE' => 'europe',
        'PT' => 'europe',

        // Asia Pacific
        'AU' => 'asia_pacific',
        'NZ' => 'asia_pacific',
        'JP' => 'asia_pacific',
        'KR' => 'asia_pacific',
        'SG' => 'asia_pacific',
        'HK' => 'asia_pacific',

        // Default for unlisted countries
        '*' => 'international',
    ],

    /*
    |--------------------------------------------------------------------------
    | Zone Types
    |--------------------------------------------------------------------------
    |
    | Configure shipping rates and delivery estimates for each zone type.
    | Rates are in USD cents.
    |
    */
    'zone_types' => [
        'domestic' => [
            'name' => 'United States',
            'base_rate' => 599, // $5.99
            'per_pound_rate' => 50, // $0.50
            'free_shipping_threshold' => 7500, // $75.00
            'available_services' => ['ground', 'two_day', 'overnight'],
            'requires_customs' => false,
        ],
        'canada' => [
            'name' => 'Canada',
            'base_rate' => 1499, // $14.99
            'per_pound_rate' => 100, // $1.00
            'free_shipping_threshold' => 15000, // $150.00
            'available_services' => ['standard', 'express'],
            'requires_customs' => true,
        ],
        'mexico' => [
            'name' => 'Mexico',
            'base_rate' => 1999, // $19.99
            'per_pound_rate' => 150, // $1.50
            'free_shipping_threshold' => 20000, // $200.00
            'available_services' => ['standard', 'express'],
            'requires_customs' => true,
        ],
        'europe' => [
            'name' => 'Europe',
            'base_rate' => 2499, // $24.99
            'per_pound_rate' => 200, // $2.00
            'free_shipping_threshold' => 25000, // $250.00
            'available_services' => ['standard', 'express'],
            'requires_customs' => true,
        ],
        'asia_pacific' => [
            'name' => 'Asia Pacific',
            'base_rate' => 2999, // $29.99
            'per_pound_rate' => 250, // $2.50
            'free_shipping_threshold' => 30000, // $300.00
            'available_services' => ['standard', 'express'],
            'requires_customs' => true,
        ],
        'international' => [
            'name' => 'International',
            'base_rate' => 3499, // $34.99
            'per_pound_rate' => 300, // $3.00
            'free_shipping_threshold' => null, // No free shipping
            'available_services' => ['standard'],
            'requires_customs' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping Services
    |--------------------------------------------------------------------------
    |
    | Define available shipping services and their properties.
    |
    */
    'shipping_services' => [
        'ground' => [
            'name' => 'Ground',
            'rate_multiplier' => 1.0,
            'delivery_days' => [3, 7],
        ],
        'two_day' => [
            'name' => '2-Day Air',
            'rate_multiplier' => 2.0,
            'delivery_days' => [2, 2],
        ],
        'overnight' => [
            'name' => 'Overnight',
            'rate_multiplier' => 3.0,
            'delivery_days' => [1, 1],
        ],
        'standard' => [
            'name' => 'Standard International',
            'rate_multiplier' => 1.0,
            'delivery_days' => [7, 14],
        ],
        'express' => [
            'name' => 'Express International',
            'rate_multiplier' => 2.0,
            'delivery_days' => [3, 5],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping Estimates
    |--------------------------------------------------------------------------
    |
    | Define estimated delivery times in business days for each zone type.
    |
    */
    'shipping_estimates' => [
        'domestic' => [
            'processing' => 1,
            'transit' => [
                'ground' => [3, 7],
                'two_day' => [2, 2],
                'overnight' => [1, 1],
            ],
        ],
        'canada' => [
            'processing' => 1,
            'transit' => [
                'standard' => [5, 10],
                'express' => [2, 4],
            ],
            'customs' => [1, 3],
        ],
        'mexico' => [
            'processing' => 1,
            'transit' => [
                'standard' => [7, 14],
                'express' => [3, 5],
            ],
            'customs' => [2, 5],
        ],
        'europe' => [
            'processing' => 1,
            'transit' => [
                'standard' => [7, 14],
                'express' => [3, 5],
            ],
            'customs' => [1, 4],
        ],
        'asia_pacific' => [
            'processing' => 1,
            'transit' => [
                'standard' => [10, 21],
                'express' => [4, 7],
            ],
            'customs' => [2, 5],
        ],
        'international' => [
            'processing' => 1,
            'transit' => [
                'standard' => [14, 30],
            ],
            'customs' => [3, 7],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Address Validation
    |--------------------------------------------------------------------------
    |
    | Configure address validation rules and formatting.
    |
    */
    'address_validation' => [
        'postal_code_formats' => [
            'US' => '^\d{5}(-\d{4})?$',
            'CA' => '^[A-Z]\d[A-Z] \d[A-Z]\d$',
            'GB' => '^[A-Z]{1,2}\d[A-Z\d]? \d[A-Z]{2}$',
            '*' => '^[A-Z0-9- ]{3,10}$',
        ],
        'state_required' => ['US', 'CA', 'AU'],
        'state_label' => [
            'US' => 'State',
            'CA' => 'Province',
            'AU' => 'State/Territory',
            '*' => 'State/Region',
        ],
        'postal_label' => [
            'US' => 'ZIP Code',
            'GB' => 'Postcode',
            '*' => 'Postal Code',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Restricted Destinations
    |--------------------------------------------------------------------------
    |
    | Define countries where shipping is not available.
    |
    */
    'restricted_destinations' => [
        'CU', // Cuba
        'IR', // Iran
        'KP', // North Korea
        'SY', // Syria
        // Add other restricted countries as needed
    ],
];

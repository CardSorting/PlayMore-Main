<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Print Sizes
    |--------------------------------------------------------------------------
    |
    | Define available print sizes and their base prices. Sizes are in inches.
    | Prices are in USD cents.
    |
    */
    'sizes' => [
        '5x7' => [
            'name' => '5" x 7"',
            'width' => 5,
            'height' => 7,
            'price' => 1499, // $14.99
            'description' => 'Perfect for desk displays and small frames',
            'shipping_weight' => 0.25, // lbs
        ],
        '8x10' => [
            'name' => '8" x 10"',
            'width' => 8,
            'height' => 10,
            'price' => 2499, // $24.99
            'description' => 'Standard size for wall art and framing',
            'shipping_weight' => 0.5, // lbs
        ],
        '11x14' => [
            'name' => '11" x 14"',
            'width' => 11,
            'height' => 14,
            'price' => 3499, // $34.99
            'description' => 'Large format for statement pieces',
            'shipping_weight' => 0.75, // lbs
        ],
        '16x20' => [
            'name' => '16" x 20"',
            'width' => 16,
            'height' => 20,
            'price' => 4999, // $49.99
            'description' => 'Gallery size for impressive wall displays',
            'shipping_weight' => 1.25, // lbs
        ],
        '24x36' => [
            'name' => '24" x 36"',
            'width' => 24,
            'height' => 36,
            'price' => 8999, // $89.99
            'description' => 'Poster size for maximum impact',
            'shipping_weight' => 2.0, // lbs
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Print Materials
    |--------------------------------------------------------------------------
    |
    | Define available print materials and their properties.
    |
    */
    'materials' => [
        'premium_lustre' => [
            'name' => 'Premium Lustre',
            'description' => 'Professional-grade photo paper with a fine grain pebble texture',
            'price_multiplier' => 1.0, // Base price
            'features' => [
                'Fingerprint resistant',
                'Archival quality',
                'Rich color reproduction',
            ],
        ],
        'metallic' => [
            'name' => 'Metallic',
            'description' => 'High-gloss finish with a metallic appearance',
            'price_multiplier' => 1.5,
            'features' => [
                'Pearlescent finish',
                'Enhanced visual depth',
                'Vibrant colors',
            ],
        ],
        'canvas' => [
            'name' => 'Canvas',
            'description' => 'Gallery-wrapped canvas print',
            'price_multiplier' => 2.0,
            'features' => [
                'Ready to hang',
                'No frame needed',
                'Classic artistic look',
            ],
            'depth_options' => [
                '0.75' => [
                    'name' => '¾" Standard',
                    'price_addition' => 0,
                ],
                '1.5' => [
                    'name' => '1½" Gallery',
                    'price_addition' => 1000, // $10.00
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Production Settings
    |--------------------------------------------------------------------------
    |
    | Configure print production settings and timeframes.
    |
    */
    'production' => [
        'processing_time' => [
            'standard' => 2, // business days
            'rush' => 1, // business day
        ],
        'rush_fee' => 1500, // $15.00
        'quality_check' => true,
        'color_correction' => true,
        'max_batch_size' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Order Settings
    |--------------------------------------------------------------------------
    |
    | Configure order-related settings and business rules.
    |
    */
    'orders' => [
        'number_prefix' => 'PRT',
        'number_length' => 8,
        'statuses' => [
            'pending' => [
                'name' => 'Pending',
                'description' => 'Order created but not paid',
                'color' => 'gray',
            ],
            'processing' => [
                'name' => 'Processing',
                'description' => 'Order paid and in production',
                'color' => 'blue',
            ],
            'shipped' => [
                'name' => 'Shipped',
                'description' => 'Order shipped to customer',
                'color' => 'green',
            ],
            'completed' => [
                'name' => 'Completed',
                'description' => 'Order delivered and confirmed',
                'color' => 'green',
            ],
            'cancelled' => [
                'name' => 'Cancelled',
                'description' => 'Order cancelled',
                'color' => 'red',
            ],
        ],
        'cancellation' => [
            'allowed_statuses' => ['pending', 'processing'],
            'refund_policy' => [
                'full_refund' => ['pending'],
                'partial_refund' => ['processing'],
                'no_refund' => ['shipped', 'completed'],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping Settings
    |--------------------------------------------------------------------------
    |
    | Configure shipping-related settings and carriers.
    |
    */
    'shipping' => [
        'carriers' => [
            'usps' => [
                'name' => 'USPS',
                'tracking_url' => 'https://tools.usps.com/go/TrackConfirmAction?tLabels=',
                'services' => [
                    'first_class' => [
                        'name' => 'First Class',
                        'days' => '3-5',
                        'price' => 599, // $5.99
                    ],
                    'priority' => [
                        'name' => 'Priority',
                        'days' => '2-3',
                        'price' => 999, // $9.99
                    ],
                ],
            ],
            'ups' => [
                'name' => 'UPS',
                'tracking_url' => 'https://www.ups.com/track?tracknum=',
                'services' => [
                    'ground' => [
                        'name' => 'Ground',
                        'days' => '3-5',
                        'price' => 799, // $7.99
                    ],
                    'second_day' => [
                        'name' => '2nd Day Air',
                        'days' => '2',
                        'price' => 1499, // $14.99
                    ],
                ],
            ],
        ],
        'packaging' => [
            'tube' => [
                'name' => 'Protective Tube',
                'max_size' => '24x36',
                'price' => 499, // $4.99
            ],
            'flat' => [
                'name' => 'Flat Mailer',
                'max_size' => '16x20',
                'price' => 299, // $2.99
            ],
        ],
        'insurance' => [
            'included_coverage' => 100, // $100
            'additional_rate' => 1, // $1 per $100 of coverage
        ],
    ],
];

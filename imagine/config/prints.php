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
        '2.5x3.5' => [
            'name' => '2.5" x 3.5"',
            'dimensions' => '2.5x3.5',
            'price' => 499, // $4.99
            'description' => 'Trading card size - Perfect for collectible cards and small prints',
            'category' => 'Mini Prints',
            'use_case' => 'Trading cards, prayer cards, small keepsakes',
            'shipping_weight' => 0.1, // lbs
            'comparison_width' => 35,
            'comparison_height' => 49,
        ],
        '3.5x2' => [
            'name' => '3.5" x 2"',
            'dimensions' => '3.5x2',
            'price' => 499, // $4.99
            'description' => 'Business card size - Ideal for professional cards and small art prints',
            'category' => 'Mini Prints',
            'use_case' => 'Business cards, appointment cards, mini art prints',
            'shipping_weight' => 0.1, // lbs
            'comparison_width' => 49,
            'comparison_height' => 28,
        ],
        '4x6' => [
            'name' => '4" x 6"',
            'dimensions' => '4x6',
            'price' => 999, // $9.99
            'description' => 'Standard photo size - Perfect for photo prints and small artwork',
            'category' => 'Photo Prints',
            'use_case' => 'Photo prints, postcards, small artwork',
            'shipping_weight' => 0.15, // lbs
            'comparison_width' => 56,
            'comparison_height' => 84,
        ],
        '5x7' => [
            'name' => '5" x 7"',
            'dimensions' => '5x7',
            'price' => 1499, // $14.99
            'description' => 'Medium photo size - Ideal for desk displays and small frames',
            'category' => 'Photo Prints',
            'use_case' => 'Framed photos, greeting cards, desk displays',
            'shipping_weight' => 0.25, // lbs
            'comparison_width' => 70,  // scaled for visualization
            'comparison_height' => 98,
        ],
        '8x10' => [
            'name' => '8" x 10"',
            'dimensions' => '8x10',
            'price' => 2499, // $24.99
            'description' => 'Classic wall art size - Perfect for framed artwork',
            'category' => 'Wall Art',
            'use_case' => 'Framed artwork, professional portraits, wall displays',
            'shipping_weight' => 0.5, // lbs
            'comparison_width' => 96,
            'comparison_height' => 120,
        ],
        '11x14' => [
            'name' => '11" x 14"',
            'dimensions' => '11x14',
            'price' => 3499, // $34.99
            'description' => 'Large format size - Ideal for statement pieces',
            'category' => 'Wall Art',
            'use_case' => 'Statement pieces, large portraits, office artwork',
            'shipping_weight' => 0.75, // lbs
            'comparison_width' => 110,
            'comparison_height' => 140,
        ],
        '16x20' => [
            'name' => '16" x 20"',
            'dimensions' => '16x20',
            'price' => 4999, // $49.99
            'description' => 'Gallery size - Perfect for impressive wall displays',
            'category' => 'Gallery Prints',
            'use_case' => 'Gallery displays, large wall art, professional installations',
            'shipping_weight' => 1.25, // lbs
            'comparison_width' => 128,
            'comparison_height' => 160,
        ],
        '24x36' => [
            'name' => '24" x 36"',
            'dimensions' => '24x36',
            'price' => 8999, // $89.99
            'description' => 'Poster size - Designed for maximum visual impact',
            'category' => 'Gallery Prints',
            'use_case' => 'Large format artwork, exhibition pieces, commercial displays',
            'shipping_weight' => 2.0, // lbs
            'comparison_width' => 144,
            'comparison_height' => 216,
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

<?php

return [
    'materials' => [
        'matte' => [
            'name' => 'Matte Paper',
            'description' => 'Premium matte finish with rich, vibrant colors',
            'features' => [
                'Non-reflective surface ideal for viewing in any light',
                'Smooth, professional finish',
                'Archival quality that lasts for decades'
            ],
            'price_multiplier' => 1.0
        ],
        'glossy' => [
            'name' => 'Glossy Paper',
            'description' => 'High-gloss finish for maximum color impact',
            'features' => [
                'Brilliant, sharp colors',
                'Professional photo lab quality',
                'Perfect for vibrant images'
            ],
            'price_multiplier' => 1.1
        ],
        'canvas' => [
            'name' => 'Canvas',
            'description' => 'Gallery-quality canvas with textured surface',
            'features' => [
                'Traditional artist canvas texture',
                'Museum-quality materials',
                'Ready to hang with included mounting hardware'
            ],
            'price_multiplier' => 1.5
        ]
    ],
    'sizes' => [
        [
            'category' => 'Mini Prints',
            'sizes' => [
                'Trading Card' => [
                    'width' => 2.5,
                    'height' => 3.5,
                    'comparison_object' => 'a playing card',
                    'price' => 999
                ],
                'Postcard' => [
                    'width' => 4,
                    'height' => 6,
                    'comparison_object' => 'a postcard',
                    'price' => 1499
                ]
            ]
        ],
        [
            'category' => 'Photo Prints',
            'sizes' => [
                '5×7' => [
                    'width' => 5,
                    'height' => 7,
                    'comparison_object' => 'a standard photo',
                    'price' => 1999
                ],
                '8×10' => [
                    'width' => 8,
                    'height' => 10,
                    'comparison_object' => 'a classic portrait',
                    'price' => 2499
                ]
            ]
        ],
        [
            'category' => 'Wall Art',
            'sizes' => [
                '11×14' => [
                    'width' => 11,
                    'height' => 14,
                    'comparison_object' => 'a small poster',
                    'price' => 3499
                ],
                '16×20' => [
                    'width' => 16,
                    'height' => 20,
                    'comparison_object' => 'a medium poster',
                    'price' => 4999
                ],
                '18×24' => [
                    'width' => 18,
                    'height' => 24,
                    'comparison_object' => 'a large poster',
                    'price' => 5999
                ]
            ]
        ],
        [
            'category' => 'Gallery Prints',
            'sizes' => [
                '20×30' => [
                    'width' => 20,
                    'height' => 30,
                    'comparison_object' => 'a small canvas',
                    'price' => 7999
                ],
                '24×36' => [
                    'width' => 24,
                    'height' => 36,
                    'comparison_object' => 'a medium canvas',
                    'price' => 9999
                ],
                '30×40' => [
                    'width' => 30,
                    'height' => 40,
                    'comparison_object' => 'a large canvas',
                    'price' => 12999
                ],
                '40×60' => [
                    'width' => 40,
                    'height' => 60,
                    'comparison_object' => 'a gallery canvas',
                    'price' => 19999
                ]
            ]
        ]
    ]
];

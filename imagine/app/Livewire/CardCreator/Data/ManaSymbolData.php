<?php

namespace App\Livewire\CardCreator\Data;

class ManaSymbolData
{
    public static function getSymbols(): array
    {
        return [
            'Colors' => [
                'W' => [
                    'description' => 'White mana - Used for protection, healing, and order',
                    'icon' => 'mana-w'
                ],
                'U' => [
                    'description' => 'Blue mana - Used for control, knowledge, and illusion',
                    'icon' => 'mana-u'
                ],
                'B' => [
                    'description' => 'Black mana - Used for death, ambition, and corruption',
                    'icon' => 'mana-b'
                ],
                'R' => [
                    'description' => 'Red mana - Used for damage, chaos, and passion',
                    'icon' => 'mana-r'
                ],
                'G' => [
                    'description' => 'Green mana - Used for growth, nature, and strength',
                    'icon' => 'mana-g'
                ]
            ],
            'Numbers' => [
                '0' => ['description' => 'Zero generic mana'],
                '1' => ['description' => 'One generic mana'],
                '2' => ['description' => 'Two generic mana'],
                '3' => ['description' => 'Three generic mana'],
                '4' => ['description' => 'Four generic mana'],
                '5' => ['description' => 'Five generic mana'],
                '6' => ['description' => 'Six generic mana'],
                '7' => ['description' => 'Seven generic mana'],
                '8' => ['description' => 'Eight generic mana'],
                '9' => ['description' => 'Nine generic mana'],
                'X' => ['description' => 'Variable amount of mana']
            ],
            'Hybrid' => [
                'W/U' => [
                    'description' => 'White or Blue mana',
                    'icon' => 'mana-wu'
                ],
                'U/B' => [
                    'description' => 'Blue or Black mana',
                    'icon' => 'mana-ub'
                ],
                'B/R' => [
                    'description' => 'Black or Red mana',
                    'icon' => 'mana-br'
                ],
                'R/G' => [
                    'description' => 'Red or Green mana',
                    'icon' => 'mana-rg'
                ],
                'G/W' => [
                    'description' => 'Green or White mana',
                    'icon' => 'mana-gw'
                ],
                'W/B' => [
                    'description' => 'White or Black mana',
                    'icon' => 'mana-wb'
                ],
                'U/R' => [
                    'description' => 'Blue or Red mana',
                    'icon' => 'mana-ur'
                ],
                'B/G' => [
                    'description' => 'Black or Green mana',
                    'icon' => 'mana-bg'
                ],
                'R/W' => [
                    'description' => 'Red or White mana',
                    'icon' => 'mana-rw'
                ],
                'G/U' => [
                    'description' => 'Green or Blue mana',
                    'icon' => 'mana-gu'
                ]
            ],
            'Phyrexian' => [
                'W/P' => [
                    'description' => 'White mana or 2 life',
                    'icon' => 'mana-wp'
                ],
                'U/P' => [
                    'description' => 'Blue mana or 2 life',
                    'icon' => 'mana-up'
                ],
                'B/P' => [
                    'description' => 'Black mana or 2 life',
                    'icon' => 'mana-bp'
                ],
                'R/P' => [
                    'description' => 'Red mana or 2 life',
                    'icon' => 'mana-rp'
                ],
                'G/P' => [
                    'description' => 'Green mana or 2 life',
                    'icon' => 'mana-gp'
                ]
            ]
        ];
    }

    public static function validateManaSymbol(string $symbol): bool
    {
        foreach (self::getSymbols() as $category => $symbols) {
            if (array_key_exists($symbol, $symbols)) {
                return true;
            }
        }
        return false;
    }

    public static function parseManaString(string $manaCost): array
    {
        if (empty($manaCost)) {
            return [];
        }

        $symbols = [];
        $current = '';
        $inBraces = false;

        for ($i = 0; $i < strlen($manaCost); $i++) {
            $char = $manaCost[$i];

            if ($char === '{') {
                $inBraces = true;
                continue;
            }

            if ($char === '}') {
                $inBraces = false;
                if (!empty($current)) {
                    $symbols[] = $current;
                    $current = '';
                }
                continue;
            }

            if ($inBraces) {
                $current .= $char;
            }
        }

        return array_filter($symbols);
    }

    public static function getSymbolDescription(string $symbol): string
    {
        foreach (self::getSymbols() as $symbols) {
            if (isset($symbols[$symbol])) {
                return $symbols[$symbol]['description'] ?? '';
            }
        }
        return '';
    }

    public static function getCategories(): array
    {
        return array_keys(self::getSymbols());
    }

    public static function getSymbolsByCategory(string $category): array
    {
        return self::getSymbols()[$category] ?? [];
    }

    public static function formatManaSymbol(string $symbol): string
    {
        return "{" . $symbol . "}";
    }

    public static function getColorIdentity(array $symbols): array
    {
        $colors = [];
        $colorMap = [
            'W' => 'White',
            'U' => 'Blue',
            'B' => 'Black',
            'R' => 'Red',
            'G' => 'Green'
        ];

        foreach ($symbols as $symbol) {
            foreach ($colorMap as $letter => $color) {
                if (str_contains($symbol, $letter)) {
                    $colors[$color] = true;
                }
            }
        }

        return array_keys($colors);
    }

    public static function calculateConvertedManaCost(array $symbols): int
    {
        $total = 0;

        foreach ($symbols as $symbol) {
            if (is_numeric($symbol)) {
                $total += (int) $symbol;
            } elseif (str_contains($symbol, '/')) {
                // Hybrid and Phyrexian mana symbols count as 1
                $total += 1;
            } else {
                // Colored mana symbols count as 1
                $total += 1;
            }
        }

        return $total;
    }
}

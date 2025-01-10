<?php

namespace App\Livewire\CardCreator\Data;

class AbilityTemplateData
{
    public static function getTemplates(): array
    {
        return [
            'Combat' => [
                'flying' => [
                    'text' => 'Flying',
                    'description' => 'This creature can only be blocked by creatures with flying or reach.',
                    'icon' => 'heroicon-o-cloud'
                ],
                'first_strike' => [
                    'text' => 'First strike',
                    'description' => 'This creature deals combat damage before creatures without first strike.',
                    'icon' => 'heroicon-o-lightning-bolt'
                ],
                'double_strike' => [
                    'text' => 'Double strike',
                    'description' => 'This creature deals both first strike and regular combat damage.',
                    'icon' => 'heroicon-o-bolt'
                ],
                'deathtouch' => [
                    'text' => 'Deathtouch',
                    'description' => 'Any amount of damage this deals to a creature is enough to destroy it.',
                    'icon' => 'heroicon-o-skull'
                ],
                'vigilance' => [
                    'text' => 'Vigilance',
                    'description' => 'Attacking doesn\'t cause this creature to tap.',
                    'icon' => 'heroicon-o-eye'
                ],
                'reach' => [
                    'text' => 'Reach',
                    'description' => 'This creature can block creatures with flying.',
                    'icon' => 'heroicon-o-hand-raised'
                ],
                'trample' => [
                    'text' => 'Trample',
                    'description' => 'This creature can deal excess combat damage to the player or planeswalker it\'s attacking.',
                    'icon' => 'heroicon-o-arrow-trending-up'
                ],
                'menace' => [
                    'text' => 'Menace',
                    'description' => 'This creature can\'t be blocked except by two or more creatures.',
                    'icon' => 'heroicon-o-exclamation-triangle'
                ]
            ],
            'Protection' => [
                'hexproof' => [
                    'text' => 'Hexproof',
                    'description' => 'This permanent can\'t be the target of spells or abilities your opponents control.',
                    'icon' => 'heroicon-o-shield-check'
                ],
                'indestructible' => [
                    'text' => 'Indestructible',
                    'description' => 'Effects that would destroy this permanent don\'t destroy it.',
                    'icon' => 'heroicon-o-shield-exclamation'
                ],
                'protection_white' => [
                    'text' => 'Protection from white',
                    'description' => 'This creature can\'t be blocked, targeted, dealt damage, or enchanted by anything white.',
                    'icon' => 'heroicon-o-sun'
                ],
                'protection_blue' => [
                    'text' => 'Protection from blue',
                    'description' => 'This creature can\'t be blocked, targeted, dealt damage, or enchanted by anything blue.',
                    'icon' => 'heroicon-o-cloud'
                ],
                'protection_black' => [
                    'text' => 'Protection from black',
                    'description' => 'This creature can\'t be blocked, targeted, dealt damage, or enchanted by anything black.',
                    'icon' => 'heroicon-o-moon'
                ],
                'protection_red' => [
                    'text' => 'Protection from red',
                    'description' => 'This creature can\'t be blocked, targeted, dealt damage, or enchanted by anything red.',
                    'icon' => 'heroicon-o-fire'
                ],
                'protection_green' => [
                    'text' => 'Protection from green',
                    'description' => 'This creature can\'t be blocked, targeted, dealt damage, or enchanted by anything green.',
                    'icon' => 'heroicon-o-tree'
                ]
            ],
            'Triggered' => [
                'etb_draw' => [
                    'text' => 'When [CARDNAME] enters the battlefield, draw a card.',
                    'description' => 'Triggers when this permanent enters the battlefield.',
                    'icon' => 'heroicon-o-arrow-right-circle'
                ],
                'dies_trigger' => [
                    'text' => 'When [CARDNAME] dies, each opponent loses 2 life.',
                    'description' => 'Triggers when this creature is put into a graveyard from the battlefield.',
                    'icon' => 'heroicon-o-x-circle'
                ],
                'attack_trigger' => [
                    'text' => 'Whenever [CARDNAME] attacks, create a 1/1 white Soldier creature token.',
                    'description' => 'Triggers whenever this creature attacks.',
                    'icon' => 'heroicon-o-play'
                ],
                'upkeep_trigger' => [
                    'text' => 'At the beginning of your upkeep, [CARDNAME] deals 1 damage to each opponent.',
                    'description' => 'Triggers at the beginning of your upkeep step.',
                    'icon' => 'heroicon-o-clock'
                ]
            ],
            'Activated' => [
                'tap_ability' => [
                    'text' => '{T}: Add one mana of any color.',
                    'description' => 'An ability that requires tapping this permanent.',
                    'icon' => 'heroicon-o-arrow-path'
                ],
                'pay_life' => [
                    'text' => 'Pay 2 life: [CARDNAME] gets +1/+1 until end of turn.',
                    'description' => 'An ability that requires paying life.',
                    'icon' => 'heroicon-o-heart'
                ],
                'sacrifice' => [
                    'text' => 'Sacrifice [CARDNAME]: Draw two cards.',
                    'description' => 'An ability that requires sacrificing this permanent.',
                    'icon' => 'heroicon-o-x-mark'
                ],
                'mana_ability' => [
                    'text' => '{2}{W}: [CARDNAME] gains flying until end of turn.',
                    'description' => 'An ability that requires paying mana.',
                    'icon' => 'heroicon-o-currency-dollar'
                ]
            ]
        ];
    }

    public static function getCategories(): array
    {
        return array_keys(self::getTemplates());
    }

    public static function getCategoryDescription(string $category): string
    {
        $descriptions = [
            'Combat' => 'Abilities that affect combat and creature interactions',
            'Protection' => 'Abilities that protect the creature from various effects',
            'Triggered' => 'Abilities that trigger when certain conditions are met',
            'Activated' => 'Abilities that can be activated by paying a cost'
        ];

        return $descriptions[$category] ?? '';
    }

    public static function validateAbility(string $category, string $key): bool
    {
        return isset(self::getTemplates()[$category][$key]);
    }

    public static function getTemplateByKey(string $category, string $key): ?array
    {
        return self::getTemplates()[$category][$key] ?? null;
    }

    public static function formatAbilityText(string $text, string $cardName = ''): string
    {
        return str_replace('[CARDNAME]', $cardName ?: 'This creature', $text);
    }

    public static function getTemplatesByCategory(string $category): array
    {
        return self::getTemplates()[$category] ?? [];
    }

    public static function categorizeAbility(string $abilityText): ?string
    {
        foreach (self::getTemplates() as $category => $abilities) {
            foreach ($abilities as $key => $template) {
                $pattern = preg_quote($template['text'], '/');
                $pattern = str_replace('\[CARDNAME\]', '[^\.]+', $pattern);
                if (preg_match("/{$pattern}/i", $abilityText)) {
                    return $category;
                }
            }
        }
        return null;
    }

    public static function validateAbilityText(string $text): bool
    {
        // Basic validation rules
        if (empty($text)) {
            return false;
        }

        // Check for balanced curly braces (mana symbols)
        if (substr_count($text, '{') !== substr_count($text, '}')) {
            return false;
        }

        // Check for proper line endings
        if (!str_ends_with($text, '.')) {
            return false;
        }

        // Check for proper capitalization
        if (!preg_match('/^[A-Z]/', $text)) {
            return false;
        }

        return true;
    }

    public static function suggestAbilityCompletion(string $partialText): array
    {
        $suggestions = [];
        $partialText = strtolower($partialText);

        foreach (self::getTemplates() as $category => $abilities) {
            foreach ($abilities as $key => $template) {
                if (str_starts_with(strtolower($template['text']), $partialText)) {
                    $suggestions[] = [
                        'category' => $category,
                        'key' => $key,
                        'text' => $template['text']
                    ];
                }
            }
        }

        return $suggestions;
    }
}

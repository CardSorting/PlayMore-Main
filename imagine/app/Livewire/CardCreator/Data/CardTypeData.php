<?php

namespace App\Livewire\CardCreator\Data;

class CardTypeData
{
    public static function getTypes(): array
    {
        return [
            'Creatures' => [
                'Creature' => [
                    'icon' => 'heroicon-o-user-group',
                    'description' => 'A being that can attack and block'
                ],
                'Legendary Creature' => [
                    'icon' => 'heroicon-o-star',
                    'description' => 'A unique creature of which only one copy can be in play'
                ],
                'Artifact Creature' => [
                    'icon' => 'heroicon-o-cog',
                    'description' => 'A creature made of metal or other artificial materials'
                ],
                'Enchantment Creature' => [
                    'icon' => 'heroicon-o-sparkles',
                    'description' => 'A creature empowered by magical enchantments'
                ]
            ],
            'Spells' => [
                'Instant' => [
                    'icon' => 'heroicon-o-bolt',
                    'description' => 'A spell that can be cast at any time'
                ],
                'Sorcery' => [
                    'icon' => 'heroicon-o-fire',
                    'description' => 'A powerful spell that can only be cast during your main phase'
                ]
            ],
            'Permanents' => [
                'Artifact' => [
                    'icon' => 'heroicon-o-cube',
                    'description' => 'A magical item or construct'
                ],
                'Enchantment' => [
                    'icon' => 'heroicon-o-sun',
                    'description' => 'A persistent magical effect'
                ],
                'Land' => [
                    'icon' => 'heroicon-o-map',
                    'description' => 'A source of mana and magical power'
                ],
                'Planeswalker' => [
                    'icon' => 'heroicon-o-user-circle',
                    'description' => 'A powerful ally who can be called upon for aid'
                ]
            ]
        ];
    }

    public static function getCreatureTypes(): array
    {
        return [
            'Humanoid' => [
                'Human',
                'Elf',
                'Dwarf',
                'Goblin',
                'Merfolk',
                'Vampire',
                'Zombie',
                'Warrior',
                'Wizard',
                'Knight',
                'Cleric',
                'Rogue'
            ],
            'Beast' => [
                'Dragon',
                'Beast',
                'Bird',
                'Cat',
                'Wolf',
                'Bear',
                'Snake',
                'Spider',
                'Insect',
                'Wurm',
                'Hydra',
                'Phoenix'
            ],
            'Mythical' => [
                'Angel',
                'Demon',
                'Spirit',
                'Elemental',
                'Giant',
                'Unicorn',
                'Griffin',
                'Sphinx',
                'Avatar',
                'God',
                'Horror',
                'Nightmare'
            ],
            'Construct' => [
                'Golem',
                'Construct',
                'Drone',
                'Myr',
                'Scarecrow',
                'Thopter',
                'Assembly-Worker',
                'Juggernaut',
                'Servo',
                'Automaton',
                'Robot',
                'Machine'
            ]
        ];
    }

    public static function getCategories(): array
    {
        return array_keys(self::getTypes());
    }

    public static function getCategoryDescription(string $category): string
    {
        $descriptions = [
            'Creatures' => 'Beings that can attack and defend',
            'Spells' => 'One-time effects that resolve immediately',
            'Permanents' => 'Cards that stay on the battlefield'
        ];

        return $descriptions[$category] ?? '';
    }

    public static function validateType(string $type): bool
    {
        foreach (self::getTypes() as $category => $types) {
            if (array_key_exists($type, $types)) {
                return true;
            }
        }
        return false;
    }

    public static function validateCreatureType(string $type): bool
    {
        foreach (self::getCreatureTypes() as $category => $types) {
            if (in_array($type, $types)) {
                return true;
            }
        }
        return false;
    }

    public static function getTypeCategory(string $type): ?string
    {
        foreach (self::getTypes() as $category => $types) {
            if (array_key_exists($type, $types)) {
                return $category;
            }
        }
        return null;
    }

    public static function getTypeDetails(string $type): ?array
    {
        foreach (self::getTypes() as $types) {
            if (isset($types[$type])) {
                return $types[$type];
            }
        }
        return null;
    }

    public static function getCreatureTypeCategories(): array
    {
        return array_keys(self::getCreatureTypes());
    }

    public static function getCreatureTypesByCategory(string $category): array
    {
        return self::getCreatureTypes()[$category] ?? [];
    }

    public static function formatCreatureType(string $baseType, string $subType): string
    {
        if (!self::validateCreatureType($subType)) {
            throw new \InvalidArgumentException("Invalid creature type: {$subType}");
        }

        return trim("{$baseType} - {$subType}");
    }
}

<?php

namespace App\Livewire\CardCreator\Services;

use App\Livewire\CardCreator\Data\CardTypeData;

class CardTypeService
{
    public function getCategories(): array
    {
        return array_keys(CardTypeData::getTypes());
    }

    public function getTypesByCategory(string $category): array
    {
        return CardTypeData::getTypes()[$category] ?? [];
    }

    public function validateType(string $type): bool
    {
        foreach (CardTypeData::getTypes() as $category => $types) {
            if (array_key_exists($type, $types)) {
                return true;
            }
        }
        return false;
    }

    public function getTypeDetails(string $type): ?array
    {
        foreach (CardTypeData::getTypes() as $types) {
            if (isset($types[$type])) {
                return $types[$type];
            }
        }
        return null;
    }

    public function getCreatureTypes(): array
    {
        return CardTypeData::getCreatureTypes();
    }

    public function validateCreatureType(string $type): bool
    {
        foreach (CardTypeData::getCreatureTypes() as $category => $types) {
            if (in_array($type, $types)) {
                return true;
            }
        }
        return false;
    }

    public function formatCreatureType(string $baseType, string $subType): string
    {
        if (!$this->validateCreatureType($subType)) {
            throw new \InvalidArgumentException("Invalid creature type: {$subType}");
        }

        return trim("{$baseType} - {$subType}");
    }

    public function parseCreatureType(string $cardType): array
    {
        $parts = explode(' - ', $cardType);
        return [
            'baseType' => $parts[0] ?? '',
            'subType' => $parts[1] ?? ''
        ];
    }

    public function isCreatureType(string $cardType): bool
    {
        return str_starts_with($cardType, 'Creature');
    }

    public function getTypeCategory(string $type): ?string
    {
        foreach (CardTypeData::getTypes() as $category => $types) {
            if (array_key_exists($type, $types)) {
                return $category;
            }
        }
        return null;
    }

    public function getCreatureTypesByCategory(string $category): array
    {
        return CardTypeData::getCreatureTypes()[$category] ?? [];
    }

    public function getCreatureTypeCategories(): array
    {
        return array_keys(CardTypeData::getCreatureTypes());
    }

    public function getCategoryDescription(string $category): string
    {
        $descriptions = [
            'Creatures' => 'Beings that can attack and defend',
            'Spells' => 'One-time effects that resolve immediately',
            'Permanents' => 'Cards that stay on the battlefield'
        ];

        return $descriptions[$category] ?? '';
    }
}

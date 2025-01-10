<?php

namespace App\Livewire\CardCreator\Services;

use App\Livewire\CardCreator\Data\ManaSymbolData;

class ManaSymbolService
{
    private const MAX_MANA_LENGTH = 15;

    public function getCategories(): array
    {
        return array_keys(ManaSymbolData::getSymbols());
    }

    public function getSymbolsByCategory(string $category): array
    {
        return ManaSymbolData::getSymbols()[$category] ?? [];
    }

    public function addSymbol(string $currentManaCost, string $symbol): string
    {
        if (strlen($currentManaCost) >= self::MAX_MANA_LENGTH) {
            throw new \LengthException('Maximum mana cost length exceeded');
        }

        if (!ManaSymbolData::validateManaSymbol($symbol)) {
            throw new \InvalidArgumentException("Invalid mana symbol: {$symbol}");
        }

        return $currentManaCost . $symbol;
    }

    public function removeLastSymbol(string $manaCost): string
    {
        if (empty($manaCost)) {
            return '';
        }

        $symbols = ManaSymbolData::parseManaString($manaCost);
        array_pop($symbols);
        
        return implode('', $symbols);
    }

    public function parseManaCost(string $manaCost): array
    {
        return ManaSymbolData::parseManaString($manaCost);
    }

    public function validateManaCost(string $manaCost): bool
    {
        if (strlen($manaCost) > self::MAX_MANA_LENGTH) {
            return false;
        }

        $symbols = $this->parseManaCost($manaCost);
        foreach ($symbols as $symbol) {
            if (!ManaSymbolData::validateManaSymbol($symbol)) {
                return false;
            }
        }

        return true;
    }

    public function getSymbolDescription(string $symbol): string
    {
        return ManaSymbolData::getSymbolDescription($symbol);
    }

    public function calculateConvertedManaCost(string $manaCost): int
    {
        $total = 0;
        $symbols = $this->parseManaCost($manaCost);

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

    public function getColorIdentity(string $manaCost): array
    {
        $colors = [];
        $colorMap = [
            'W' => 'White',
            'U' => 'Blue',
            'B' => 'Black',
            'R' => 'Red',
            'G' => 'Green'
        ];

        $symbols = $this->parseManaCost($manaCost);
        foreach ($symbols as $symbol) {
            foreach ($colorMap as $letter => $color) {
                if (str_contains($symbol, $letter)) {
                    $colors[$color] = true;
                }
            }
        }

        return array_keys($colors);
    }

    public function formatManaCost(string $manaCost): string
    {
        $symbols = $this->parseManaCost($manaCost);
        return implode('', $symbols);
    }

    public function getMaxLength(): int
    {
        return self::MAX_MANA_LENGTH;
    }
}

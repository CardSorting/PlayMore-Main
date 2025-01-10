<?php

namespace App\Livewire\CardCreator\Services;

use App\Livewire\CardCreator\Data\AbilityTemplateData;

class AbilityService
{
    public function getCategories(): array
    {
        return AbilityTemplateData::getCategories();
    }

    public function getCategoryDescription(string $category): string
    {
        return AbilityTemplateData::getCategoryDescription($category);
    }

    public function getTemplatesByCategory(string $category): array
    {
        return AbilityTemplateData::getTemplates()[$category] ?? [];
    }

    public function addAbility(string $currentAbilities, string $category, string $key, string $cardName = ''): string
    {
        $template = AbilityTemplateData::getTemplateByKey($category, $key);
        if (!$template) {
            throw new \InvalidArgumentException("Invalid ability template: {$category}/{$key}");
        }

        $abilityText = AbilityTemplateData::formatAbilityText($template['text'], $cardName);

        return $this->appendAbility($currentAbilities, $abilityText);
    }

    public function appendAbility(string $currentAbilities, string $newAbility): string
    {
        if (empty($currentAbilities)) {
            return $newAbility;
        }

        return $currentAbilities . "\n" . $newAbility;
    }

    public function validateAbility(string $category, string $key): bool
    {
        return AbilityTemplateData::validateAbility($category, $key);
    }

    public function getAbilityIcon(string $category, string $key): ?string
    {
        $template = AbilityTemplateData::getTemplateByKey($category, $key);
        return $template['icon'] ?? null;
    }

    public function getAbilityDescription(string $category, string $key): ?string
    {
        $template = AbilityTemplateData::getTemplateByKey($category, $key);
        return $template['description'] ?? null;
    }

    public function parseAbilities(string $abilities): array
    {
        return array_filter(array_map('trim', explode("\n", $abilities)));
    }

    public function formatAbilities(array $abilities): string
    {
        return implode("\n", array_filter($abilities));
    }

    public function replaceCardNameInAbilities(string $abilities, string $cardName): string
    {
        return str_replace('[CARDNAME]', $cardName ?: 'This creature', $abilities);
    }

    public function categorizeAbility(string $abilityText): ?string
    {
        foreach (AbilityTemplateData::getTemplates() as $category => $abilities) {
            foreach ($abilities as $key => $template) {
                if (str_contains($abilityText, $template['text'])) {
                    return $category;
                }
            }
        }
        return null;
    }

    public function validateAbilityText(string $abilityText): bool
    {
        // Basic validation rules
        $maxLength = 500;
        if (strlen($abilityText) > $maxLength) {
            return false;
        }

        // Check for balanced curly braces (mana symbols)
        if (substr_count($abilityText, '{') !== substr_count($abilityText, '}')) {
            return false;
        }

        // Check for common MTG syntax patterns
        $validPatterns = [
            '/\{[WUBRGP0-9\/]+\}/', // Mana symbols
            '/^[A-Z]/', // Starts with capital letter
            '/\.$/' // Ends with period
        ];

        foreach ($validPatterns as $pattern) {
            if (preg_match($pattern, $abilityText) === 0) {
                return false;
            }
        }

        return true;
    }

    public function suggestAbilityCompletion(string $partialText): array
    {
        $suggestions = [];
        foreach (AbilityTemplateData::getTemplates() as $category => $abilities) {
            foreach ($abilities as $key => $template) {
                if (str_starts_with(strtolower($template['text']), strtolower($partialText))) {
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

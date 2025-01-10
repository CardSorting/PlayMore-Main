<?php

namespace App\Livewire\CardCreator\Traits;

trait WithCardValidation
{
    protected function validateCardName(string $name): bool
    {
        if (empty($name)) {
            $this->addError('name', 'Card name is required');
            return false;
        }

        if (strlen($name) > 255) {
            $this->addError('name', 'Card name cannot exceed 255 characters');
            return false;
        }

        // Check for valid MTG card name format
        if (!preg_match('/^[A-Z][a-zA-Z\s\',]*$/', $name)) {
            $this->addError('name', 'Card name must start with a capital letter and contain only letters, spaces, apostrophes, and commas');
            return false;
        }

        return true;
    }

    protected function validateManaCost(string $manaCost): bool
    {
        if (empty($manaCost)) {
            $this->addError('manaCost', 'Mana cost is required');
            return false;
        }

        if (strlen($manaCost) > 50) {
            $this->addError('manaCost', 'Mana cost cannot exceed 50 characters');
            return false;
        }

        // Check for balanced curly braces
        if (substr_count($manaCost, '{') !== substr_count($manaCost, '}')) {
            $this->addError('manaCost', 'Mana cost has unbalanced curly braces');
            return false;
        }

        return true;
    }

    protected function validateCardType(string $cardType): bool
    {
        if (empty($cardType)) {
            $this->addError('cardType', 'Card type is required');
            return false;
        }

        if (strlen($cardType) > 255) {
            $this->addError('cardType', 'Card type cannot exceed 255 characters');
            return false;
        }

        // Validate basic card type format
        if (!preg_match('/^(Creature|Instant|Sorcery|Enchantment|Artifact|Land)(\s-\s[A-Za-z\s]+)?$/', $cardType)) {
            $this->addError('cardType', 'Invalid card type format');
            return false;
        }

        return true;
    }

    protected function validateAbilities(?string $abilities): bool
    {
        if (empty($abilities)) {
            return true; // Abilities are optional
        }

        // Check for balanced curly braces (mana symbols)
        if (substr_count($abilities, '{') !== substr_count($abilities, '}')) {
            $this->addError('abilities', 'Abilities have unbalanced curly braces');
            return false;
        }

        // Check for proper line endings
        $lines = explode("\n", $abilities);
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line) && !str_ends_with($line, '.')) {
                $this->addError('abilities', 'Each ability must end with a period');
                return false;
            }
        }

        return true;
    }

    protected function validateFlavorText(?string $flavorText): bool
    {
        if (empty($flavorText)) {
            return true; // Flavor text is optional
        }

        if (strlen($flavorText) > 500) {
            $this->addError('flavorText', 'Flavor text cannot exceed 500 characters');
            return false;
        }

        // Check for proper quotation marks and italics
        if (substr_count($flavorText, '"') % 2 !== 0) {
            $this->addError('flavorText', 'Flavor text has unmatched quotation marks');
            return false;
        }

        return true;
    }

    protected function validatePowerToughness(?string $powerToughness): bool
    {
        if (empty($powerToughness)) {
            return true; // Power/Toughness is optional (for non-creatures)
        }

        // Check P/T format (e.g., "2/3", "*/4", "4/*")
        if (!preg_match('/^(\d+|\*)\/((\d+|\*))$/', $powerToughness)) {
            $this->addError('powerToughness', 'Invalid power/toughness format');
            return false;
        }

        return true;
    }

    protected function validateAll(): bool
    {
        return $this->validateCardName($this->name)
            && $this->validateManaCost($this->manaCost)
            && $this->validateCardType($this->cardType)
            && $this->validateAbilities($this->abilities)
            && $this->validateFlavorText($this->flavorText)
            && $this->validatePowerToughness($this->powerToughness);
    }

    protected function getErrorMessage(string $field): ?string
    {
        $errors = $this->getErrorBag();
        return $errors->has($field) ? $errors->first($field) : null;
    }
}

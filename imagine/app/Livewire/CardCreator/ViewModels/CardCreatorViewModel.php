<?php

namespace App\Livewire\CardCreator\ViewModels;

use App\Models\Gallery;
use App\Livewire\CardCreator\Services\CardTypeService;
use App\Livewire\CardCreator\Services\ManaSymbolService;
use App\Livewire\CardCreator\Services\AbilityService;

class CardCreatorViewModel
{
    protected $cardTypeService;
    protected $manaSymbolService;
    protected $abilityService;
    protected $image;
    protected $data;

    public function __construct(
        Gallery $image,
        array $data,
        CardTypeService $cardTypeService,
        ManaSymbolService $manaSymbolService,
        AbilityService $abilityService
    ) {
        $this->image = $image;
        $this->data = $data;
        $this->cardTypeService = $cardTypeService;
        $this->manaSymbolService = $manaSymbolService;
        $this->abilityService = $abilityService;
    }

    public function getCardPreviewData(): array
    {
        return [
            'name' => $this->data['name'] ?? '',
            'manaCost' => $this->getFormattedManaCost(),
            'cardType' => $this->data['cardType'] ?? '',
            'abilities' => $this->getFormattedAbilities(),
            'flavorText' => $this->data['flavorText'] ?? '',
            'powerToughness' => $this->data['powerToughness'] ?? '',
            'imageUrl' => $this->image->image_url,
            'imageAuthor' => $this->image->user->name,
            'colorIdentity' => $this->getColorIdentity(),
            'convertedManaCost' => $this->getConvertedManaCost()
        ];
    }

    public function getManaSelectorData(): array
    {
        return [
            'categories' => $this->manaSymbolService->getCategories(),
            'symbolsByCategory' => array_map(
                fn($category) => $this->manaSymbolService->getSymbolsByCategory($category),
                $this->manaSymbolService->getCategories()
            ),
            'currentManaCost' => $this->data['manaCost'] ?? '',
            'maxLength' => $this->manaSymbolService->getMaxLength()
        ];
    }

    public function getTypeSelectorData(): array
    {
        return [
            'categories' => $this->cardTypeService->getCategories(),
            'typesByCategory' => array_map(
                fn($category) => $this->cardTypeService->getTypesByCategory($category),
                $this->cardTypeService->getCategories()
            ),
            'currentType' => $this->data['cardType'] ?? '',
            'creatureTypes' => $this->cardTypeService->getCreatureTypes(),
            'showTypeSelector' => $this->shouldShowTypeSelector()
        ];
    }

    public function getAbilitySelectorData(): array
    {
        return [
            'categories' => $this->abilityService->getCategories(),
            'templatesByCategory' => array_map(
                fn($category) => $this->abilityService->getTemplatesByCategory($category),
                $this->abilityService->getCategories()
            ),
            'currentAbilities' => $this->data['abilities'] ?? '',
            'parsedAbilities' => $this->getParsedAbilities(),
            'abilityIcons' => $this->getAbilityIcons()
        ];
    }

    protected function getFormattedManaCost(): array
    {
        return $this->manaSymbolService->parseManaCost($this->data['manaCost'] ?? '');
    }

    protected function getFormattedAbilities(): string
    {
        return $this->abilityService->replaceCardNameInAbilities(
            $this->data['abilities'] ?? '',
            $this->data['name'] ?? ''
        );
    }

    protected function getColorIdentity(): array
    {
        return $this->manaSymbolService->getColorIdentity($this->data['manaCost'] ?? '');
    }

    protected function getConvertedManaCost(): int
    {
        return $this->manaSymbolService->calculateConvertedManaCost($this->data['manaCost'] ?? '');
    }

    protected function getParsedAbilities(): array
    {
        return $this->abilityService->parseAbilities($this->data['abilities'] ?? '');
    }

    protected function getAbilityIcons(): array
    {
        $icons = [];
        foreach ($this->getParsedAbilities() as $ability) {
            $category = $this->abilityService->categorizeAbility($ability);
            if ($category) {
                foreach ($this->abilityService->getTemplatesByCategory($category) as $key => $template) {
                    if (str_contains($ability, $template['text'])) {
                        $icons[] = $this->abilityService->getAbilityIcon($category, $key);
                        break;
                    }
                }
            }
        }
        return $icons;
    }

    protected function shouldShowTypeSelector(): bool
    {
        return str_starts_with($this->data['cardType'] ?? '', 'Creature');
    }

    public function toArray(): array
    {
        return [
            'preview' => $this->getCardPreviewData(),
            'manaSelector' => $this->getManaSelectorData(),
            'typeSelector' => $this->getTypeSelectorData(),
            'abilitySelector' => $this->getAbilitySelectorData()
        ];
    }
}

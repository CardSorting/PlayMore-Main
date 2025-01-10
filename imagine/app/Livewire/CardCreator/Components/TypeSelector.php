<?php

namespace App\Livewire\CardCreator\Components;

use Livewire\Component;
use App\Livewire\CardCreator\Services\CardTypeService;

class TypeSelector extends Component
{
    public $cardType = '';
    public $activeCategory = 'Creatures';
    public $showTypeSelector = false;
    public $selectedSubtypes = [];

    protected $cardTypeService;

    public function boot()
    {
        $this->cardTypeService = new CardTypeService();
    }

    public function selectCardType(string $type)
    {
        if (!$this->cardTypeService->validateType($type)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Invalid card type'
            ]);
            return;
        }

        $this->cardType = $type;
        $this->showTypeSelector = $this->cardTypeService->isCreatureType($type);
        $this->selectedSubtypes = [];
        
        $this->dispatch('cardTypeUpdated', [
            'type' => $this->cardType,
            'subtypes' => $this->selectedSubtypes
        ]);
    }

    public function addCreatureType(string $subtype)
    {
        if (!$this->cardTypeService->validateCreatureType($subtype)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Invalid creature type'
            ]);
            return;
        }

        if (!in_array($subtype, $this->selectedSubtypes)) {
            $this->selectedSubtypes[] = $subtype;
            $this->updateFullCardType();
        }
    }

    public function removeCreatureType(string $subtype)
    {
        $this->selectedSubtypes = array_filter($this->selectedSubtypes, fn($type) => $type !== $subtype);
        $this->updateFullCardType();
    }

    protected function updateFullCardType()
    {
        if (empty($this->selectedSubtypes)) {
            $fullType = $this->cardType;
        } else {
            $subtypes = implode(' ', $this->selectedSubtypes);
            $fullType = $this->cardTypeService->formatCreatureType($this->cardType, $subtypes);
        }

        $this->dispatch('cardTypeUpdated', [
            'type' => $fullType,
            'subtypes' => $this->selectedSubtypes
        ]);
    }

    public function getCategoriesProperty()
    {
        return $this->cardTypeService->getCategories();
    }

    public function getTypesByCategoryProperty()
    {
        return $this->cardTypeService->getTypesByCategory($this->activeCategory);
    }

    public function getCreatureTypesProperty()
    {
        return $this->cardTypeService->getCreatureTypes();
    }

    public function getCreatureTypeCategoriesProperty()
    {
        return $this->cardTypeService->getCreatureTypeCategories();
    }

    public function getCreatureTypesByCategoryProperty()
    {
        return array_map(
            fn($category) => $this->cardTypeService->getCreatureTypesByCategory($category),
            $this->creatureTypeCategories
        );
    }

    public function getCategoryDescriptionProperty()
    {
        return $this->cardTypeService->getCategoryDescription($this->activeCategory);
    }

    public function getTypeDetailsProperty()
    {
        return $this->cardType ? $this->cardTypeService->getTypeDetails($this->cardType) : null;
    }

    public function getSelectedTypeCategoryProperty()
    {
        return $this->cardType ? $this->cardTypeService->getTypeCategory($this->cardType) : null;
    }

    public function render()
    {
        return view('livewire.card-creator.components.type-selector', [
            'categories' => $this->categories,
            'typesByCategory' => $this->typesByCategory,
            'creatureTypes' => $this->creatureTypes,
            'creatureTypeCategories' => $this->creatureTypeCategories,
            'creatureTypesByCategory' => $this->creatureTypesByCategory,
            'categoryDescription' => $this->categoryDescription,
            'typeDetails' => $this->typeDetails,
            'selectedTypeCategory' => $this->selectedTypeCategory
        ]);
    }
}

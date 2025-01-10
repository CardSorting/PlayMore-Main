<?php

namespace App\Livewire\CardCreator\Components;

use Livewire\Component;
use App\Livewire\CardCreator\Services\ManaSymbolService;
use App\Livewire\CardCreator\Services\AbilityService;

class CardPreview extends Component
{
    public $name = '';
    public $manaCost = '';
    public $cardType = '';
    public $abilities = '';
    public $flavorText = '';
    public $powerToughness = '';
    public $image;

    protected $listeners = [
        'cardNameUpdated' => 'updateName',
        'manaCostUpdated' => 'updateManaCost',
        'cardTypeUpdated' => 'updateCardType',
        'abilitiesUpdated' => 'updateAbilities',
        'flavorTextUpdated' => 'updateFlavorText',
        'powerToughnessUpdated' => 'updatePowerToughness'
    ];

    public function mount($image)
    {
        $this->image = $image;
    }

    public function updateName($name)
    {
        $this->name = $name;
    }

    public function updateManaCost($manaCost)
    {
        $this->manaCost = $manaCost;
    }

    public function updateCardType($cardType)
    {
        $this->cardType = $cardType;
    }

    public function updateAbilities($abilities)
    {
        $this->abilities = $abilities;
    }

    public function updateFlavorText($flavorText)
    {
        $this->flavorText = $flavorText;
    }

    public function updatePowerToughness($powerToughness)
    {
        $this->powerToughness = $powerToughness;
    }

    public function getFormattedManaCostProperty()
    {
        $manaService = new ManaSymbolService();
        return $manaService->parseManaCost($this->manaCost);
    }

    public function getFormattedAbilitiesProperty()
    {
        $abilityService = new AbilityService();
        return $abilityService->replaceCardNameInAbilities($this->abilities, $this->name);
    }

    public function getColorIdentityProperty()
    {
        $manaService = new ManaSymbolService();
        return $manaService->getColorIdentity($this->manaCost);
    }

    public function getConvertedManaCostProperty()
    {
        $manaService = new ManaSymbolService();
        return $manaService->calculateConvertedManaCost($this->manaCost);
    }

    public function render()
    {
        return view('livewire.card-creator.components.card-preview', [
            'formattedManaCost' => $this->formattedManaCost,
            'formattedAbilities' => $this->formattedAbilities,
            'colorIdentity' => $this->colorIdentity,
            'convertedManaCost' => $this->convertedManaCost
        ]);
    }
}

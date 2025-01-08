<?php

namespace App\Livewire;

use Livewire\Component;

class CardDisplay extends Component
{
    public $card;
    public $showDetails = false;
    public $showFlipAnimation = false;

    public function mount($card)
    {
        // Ensure all required fields are present with defaults
        $this->card = [
            'name' => $card['name'] ?? 'Unnamed Card',
            'mana_cost' => $card['mana_cost'] ?? '',
            'card_type' => $card['card_type'] ?? 'Unknown Type',
            'abilities' => $card['abilities'] ?? 'No abilities',
            'flavor_text' => $card['flavor_text'] ?? '',
            'power_toughness' => $card['power_toughness'] ?? null,
            'rarity' => $card['rarity'] ?? 'Common',
            'image_url' => $card['image_url'] ?? '/static/images/placeholder.png',
        ];
    }

    public function getNormalizedRarity()
    {
        return strtolower(str_replace(' ', '-', $this->card['rarity']));
    }

    public function isRare()
    {
        return $this->card['rarity'] === 'Rare';
    }

    public function isMythicRare()
    {
        return $this->card['rarity'] === 'Mythic Rare';
    }

    public function isUncommon()
    {
        return $this->card['rarity'] === 'Uncommon';
    }

    public function isCommon()
    {
        return $this->card['rarity'] === 'Common';
    }

    public function getRarityClasses()
    {
        if ($this->isMythicRare()) {
            return 'mythic-rare-card mythic-holographic';
        }
        if ($this->isRare()) {
            return 'rare-card rare-holographic';
        }
        return '';
    }

    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }

    public function flipCard()
    {
        $this->showFlipAnimation = !$this->showFlipAnimation;
    }

    public function render()
    {
        return view('livewire.card-display');
    }
}

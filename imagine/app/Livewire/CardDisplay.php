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
        // Handle both array and object access
        $getData = function($key) use ($card) {
            if (is_array($card)) {
                if ($key === 'author') {
                    return $card['user']['name'] ?? 'Unknown Author';
                }
                return $card[$key] ?? null;
            }
            if ($key === 'author') {
                return optional($card->user)->name ?? 'Unknown Author';
            }
            return $card->$key ?? null;
        };

        // Ensure all required fields are present with defaults
        $this->card = [
            'name' => $getData('name') ?? 'Unnamed Card',
            'mana_cost' => $getData('mana_cost') ?? '',
            'card_type' => $getData('card_type') ?? 'Unknown Type',
            'author' => $getData('author'),
            'abilities' => $getData('abilities') ?? 'No abilities',
            'flavor_text' => $getData('flavor_text') ?? '',
            'power_toughness' => $getData('power_toughness') ?? null,
            'rarity' => $getData('rarity') ?? 'Common',
            'image_url' => $getData('image_url') ?? '/static/images/placeholder.png',
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
            return 'mythic-rare-card mythic-holographic hover:scale-105';
        }
        if ($this->isRare()) {
            return 'rare-card rare-holographic hover:scale-104';
        }
        if ($this->isUncommon()) {
            return 'uncommon-card uncommon-holographic hover:scale-103';
        }
        return 'common-card hover:scale-102';
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

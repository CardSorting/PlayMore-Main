<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardGridItem extends Component
{
    public $card;

    public function __construct($card)
    {
        $this->card = $card;
    }

    public function render()
    {
        return view('components.card-grid-item');
    }

    public function getDataAttributes()
    {
        return [
            'rarity' => strtolower(str_replace(' ', '-', $this->card['rarity'])),
            'type' => strtolower(str_replace(' ', '-', $this->card['card_type'])),
            'name' => strtolower($this->card['name']),
        ];
    }
}

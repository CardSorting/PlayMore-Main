<?php

namespace App\Livewire;

use Livewire\Component;

class CardDetailsModal extends Component
{
    public $card = null;
    public $showModal = false;

    protected $listeners = ['showCardDetails' => 'showCard'];

    public function showCard($card)
    {
        $this->card = $card;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->card = null;
    }

    public function render()
    {
        return view('livewire.card-details-modal');
    }
}

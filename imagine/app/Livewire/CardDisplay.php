<?php

namespace App\Livewire;

use App\ViewModels\CardViewModel;
use Livewire\Component;

class CardDisplay extends Component
{
    public array $card;
    public bool $showDetails = false;
    public bool $showFlipAnimation = false;
    private ?CardViewModel $viewModel = null;

    public function mount($card)
    {
        \Log::info('CardDisplay mount received card:', [
            'raw_card' => $card,
            'is_array' => is_array($card),
            'is_object' => is_object($card)
        ]);

        if (!is_array($card)) {
            \Log::error('CardDisplay expected array but received:', [
                'type' => gettype($card),
                'card' => $card
            ]);
            $card = [];
        }

        \Log::info('CardDisplay processing card data:', [
            'card_data' => $card
        ]);

        $this->viewModel = CardViewModel::fromArray($card);
        $this->card = $this->viewModel->toArray();

        \Log::info('CardDisplay final card data:', [
            'processed_card' => $this->card
        ]);
    }

    public function getNormalizedRarity(): string
    {
        return $this->viewModel->getNormalizedRarity();
    }

    public function getRarityClasses(): string
    {
        return $this->viewModel->getRarityClasses();
    }

    public function toggleDetails(): void
    {
        $this->showDetails = !$this->showDetails;
    }

    public function flipCard(): void
    {
        $this->showFlipAnimation = !$this->showFlipAnimation;
    }

    public function render()
    {
        return view('livewire.card-display');
    }
}

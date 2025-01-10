<?php

namespace App\Livewire;

use App\ViewModels\CardViewModel;
use Livewire\Component;

class CardDisplay extends Component
{
    public array $card;
    public bool $showFlipAnimation = false;
    private ?CardViewModel $viewModel = null;

    public function mount($card)
    {
        try {
            \Log::info('CardDisplay mount received card:', [
                'raw_card' => $card,
                'is_array' => is_array($card),
                'is_object' => is_object($card)
            ]);

            if (!is_array($card)) {
                throw new \InvalidArgumentException('CardDisplay expected array but received: ' . gettype($card));
            }

            $this->viewModel = CardViewModel::fromArray($card);
            $this->card = $this->viewModel->toArray();

            \Log::info('CardDisplay processed card data:', [
                'card_id' => $card['id'] ?? null,
                'name' => $this->card['name'],
                'abilities' => $this->card['abilities_array']
            ]);
        } catch (\Exception $e) {
            \Log::error('CardDisplay mount failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Provide fallback data
            $this->card = [
                'name' => 'Error Loading Card',
                'mana_cost' => '',
                'card_type' => 'Unknown Type',
                'abilities' => 'No abilities',
                'abilities_array' => [],
                'abilities_text' => '',
                'flavor_text' => '',
                'power_toughness' => null,
                'rarity' => 'Common',
                'image_url' => '/static/images/placeholder.png',
                'author' => 'Unknown Author'
            ];
        }
    }

    public function getAbilitiesDisplay(): string
    {
        return $this->card['abilities_text'] ?? $this->card['abilities'] ?? 'No abilities';
    }

    public function hasAbilities(): bool
    {
        return !empty($this->card['abilities_array']);
    }

    public function getNormalizedRarity(): string
    {
        return $this->viewModel->getNormalizedRarity();
    }

    public function getRarityClasses(): string
    {
        return $this->viewModel->getRarityClasses();
    }

    public function showDetails(): void
    {
        $this->dispatch('showCardDetails', $this->card);
    }

    public function flipCard(): void
    {
        $this->showFlipAnimation = !$this->showFlipAnimation;
        $this->dispatch('cardFlipped');
    }

    public function render()
    {
        return view('livewire.card-display');
    }
}

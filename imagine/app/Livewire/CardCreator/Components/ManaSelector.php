<?php

namespace App\Livewire\CardCreator\Components;

use Livewire\Component;
use App\Livewire\CardCreator\Services\ManaSymbolService;

class ManaSelector extends Component
{
    public $manaCost = '';
    public $activeTab = 'Colors';

    protected $manaService;

    public function boot()
    {
        $this->manaService = new ManaSymbolService();
    }

    public function addSymbol(string $symbol)
    {
        try {
            $this->manaCost = $this->manaService->addSymbol($this->manaCost, $symbol);
            $this->dispatch('manaCostUpdated', $this->manaCost);
        } catch (\LengthException $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Maximum mana cost length reached'
            ]);
        } catch (\InvalidArgumentException $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Invalid mana symbol'
            ]);
        }
    }

    public function removeLastSymbol()
    {
        $this->manaCost = $this->manaService->removeLastSymbol($this->manaCost);
        $this->dispatch('manaCostUpdated', $this->manaCost);
    }

    public function clearManaCost()
    {
        $this->manaCost = '';
        $this->dispatch('manaCostUpdated', $this->manaCost);
    }

    public function getSymbolsProperty()
    {
        return $this->manaService->getSymbols();
    }

    public function getCategoriesProperty()
    {
        return $this->manaService->getCategories();
    }

    public function getSymbolsByCategoryProperty()
    {
        return $this->manaService->getSymbolsByCategory($this->activeTab);
    }

    public function getCurrentSymbolsProperty()
    {
        return $this->manaService->parseManaCost($this->manaCost);
    }

    public function getSymbolDescriptionProperty()
    {
        $descriptions = [];
        foreach ($this->currentSymbols as $symbol) {
            $descriptions[$symbol] = $this->manaService->getSymbolDescription($symbol);
        }
        return $descriptions;
    }

    public function getMaxLengthProperty()
    {
        return $this->manaService->getMaxLength();
    }

    public function getRemainingLengthProperty()
    {
        return $this->maxLength - strlen($this->manaCost);
    }

    public function getIsFullProperty()
    {
        return $this->remainingLength <= 0;
    }

    public function updatedManaCost($value)
    {
        if ($this->manaService->validateManaCost($value)) {
            $this->dispatch('manaCostUpdated', $this->manaCost);
        } else {
            $this->manaCost = '';
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Invalid mana cost format'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.card-creator.components.mana-selector', [
            'symbols' => $this->symbols,
            'categories' => $this->categories,
            'symbolsByCategory' => $this->symbolsByCategory,
            'currentSymbols' => $this->currentSymbols,
            'symbolDescriptions' => $this->symbolDescription,
            'maxLength' => $this->maxLength,
            'remainingLength' => $this->remainingLength,
            'isFull' => $this->isFull
        ]);
    }
}

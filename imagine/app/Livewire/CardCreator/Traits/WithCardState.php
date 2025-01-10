<?php

namespace App\Livewire\CardCreator\Traits;

trait WithCardState
{
    protected function getStateKey(): string
    {
        return 'card_creator_' . auth()->id() . '_' . $this->image->id;
    }

    protected function saveState(): void
    {
        $state = [
            'name' => $this->name,
            'manaCost' => $this->manaCost,
            'cardType' => $this->cardType,
            'abilities' => $this->abilities,
            'flavorText' => $this->flavorText,
            'powerToughness' => $this->powerToughness,
            'lastUpdated' => now()->timestamp
        ];

        session()->put($this->getStateKey(), $state);
    }

    protected function loadState(): void
    {
        $state = session()->get($this->getStateKey());

        if ($state) {
            $this->name = $state['name'] ?? '';
            $this->manaCost = $state['manaCost'] ?? '';
            $this->cardType = $state['cardType'] ?? '';
            $this->abilities = $state['abilities'] ?? '';
            $this->flavorText = $state['flavorText'] ?? '';
            $this->powerToughness = $state['powerToughness'] ?? '';

            // Notify child components of restored state
            $this->dispatch('cardStateRestored', [
                'name' => $this->name,
                'manaCost' => $this->manaCost,
                'cardType' => $this->cardType,
                'abilities' => $this->abilities,
                'flavorText' => $this->flavorText,
                'powerToughness' => $this->powerToughness
            ]);
        }
    }

    protected function clearState(): void
    {
        session()->forget($this->getStateKey());
    }

    protected function hasState(): bool
    {
        return session()->has($this->getStateKey());
    }

    protected function getLastUpdateTime(): ?int
    {
        $state = session()->get($this->getStateKey());
        return $state['lastUpdated'] ?? null;
    }

    protected function isDirty(): bool
    {
        $state = session()->get($this->getStateKey());
        if (!$state) {
            return false;
        }

        return $state['name'] !== $this->name
            || $state['manaCost'] !== $this->manaCost
            || $state['cardType'] !== $this->cardType
            || $state['abilities'] !== $this->abilities
            || $state['flavorText'] !== $this->flavorText
            || $state['powerToughness'] !== $this->powerToughness;
    }

    protected function getInitialState(): array
    {
        return [
            'name' => '',
            'manaCost' => '',
            'cardType' => '',
            'abilities' => '',
            'flavorText' => '',
            'powerToughness' => ''
        ];
    }

    protected function resetState(): void
    {
        $initial = $this->getInitialState();
        
        $this->name = $initial['name'];
        $this->manaCost = $initial['manaCost'];
        $this->cardType = $initial['cardType'];
        $this->abilities = $initial['abilities'];
        $this->flavorText = $initial['flavorText'];
        $this->powerToughness = $initial['powerToughness'];

        $this->clearState();

        $this->dispatch('cardStateReset');
    }

    public function dehydrate(): void
    {
        // Save state before component is dehydrated
        if ($this->isDirty()) {
            $this->saveState();
        }
    }

    public function hydrate(): void
    {
        // Load state when component is hydrated
        if ($this->hasState()) {
            $this->loadState();
        }
    }

    // Hook into Livewire lifecycle
    public function bootWithCardState(): void
    {
        if ($this->hasState()) {
            $this->loadState();
        }
    }

    public function dehydrateWithCardState(): void
    {
        if ($this->isDirty()) {
            $this->saveState();
        }
    }
}

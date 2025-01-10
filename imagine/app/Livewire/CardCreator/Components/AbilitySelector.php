<?php

namespace App\Livewire\CardCreator\Components;

use Livewire\Component;
use App\Livewire\CardCreator\Services\AbilityService;

class AbilitySelector extends Component
{
    public $abilities = '';
    public $activeCategory = 'Combat';
    public $cardName = '';
    public $customAbility = '';

    protected $abilityService;

    protected $listeners = [
        'cardNameUpdated' => 'updateCardName'
    ];

    public function boot()
    {
        $this->abilityService = new AbilityService();
    }

    public function addAbilityTemplate(string $category, string $key)
    {
        try {
            $this->abilities = $this->abilityService->addAbility(
                $this->abilities,
                $category,
                $key,
                $this->cardName
            );
            $this->dispatch('abilitiesUpdated', $this->abilities);
        } catch (\InvalidArgumentException $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function addCustomAbility()
    {
        if (empty($this->customAbility)) {
            return;
        }

        if (!$this->abilityService->validateAbilityText($this->customAbility)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Invalid ability text format'
            ]);
            return;
        }

        $this->abilities = $this->abilityService->appendAbility(
            $this->abilities,
            $this->customAbility
        );
        $this->customAbility = '';
        $this->dispatch('abilitiesUpdated', $this->abilities);
    }

    public function removeAbility(int $index)
    {
        $abilities = $this->abilityService->parseAbilities($this->abilities);
        unset($abilities[$index]);
        $this->abilities = $this->abilityService->formatAbilities($abilities);
        $this->dispatch('abilitiesUpdated', $this->abilities);
    }

    public function clearAbilities()
    {
        $this->abilities = '';
        $this->dispatch('abilitiesUpdated', $this->abilities);
    }

    public function updateCardName($name)
    {
        $this->cardName = $name;
        $this->abilities = $this->abilityService->replaceCardNameInAbilities(
            $this->abilities,
            $this->cardName
        );
        $this->dispatch('abilitiesUpdated', $this->abilities);
    }

    public function getCategoriesProperty()
    {
        return $this->abilityService->getCategories();
    }

    public function getTemplatesByCategoryProperty()
    {
        return $this->abilityService->getTemplatesByCategory($this->activeCategory);
    }

    public function getCategoryDescriptionProperty()
    {
        return $this->abilityService->getCategoryDescription($this->activeCategory);
    }

    public function getParsedAbilitiesProperty()
    {
        return $this->abilityService->parseAbilities($this->abilities);
    }

    public function getAbilityIconsProperty()
    {
        $icons = [];
        foreach ($this->parsedAbilities as $ability) {
            $category = $this->abilityService->categorizeAbility($ability);
            if ($category) {
                foreach ($this->abilityService->getTemplatesByCategory($category) as $key => $template) {
                    if (str_contains($ability, $template['text'])) {
                        $icons[] = $this->abilityService->getAbilityIcon($category, $key);
                        break;
                    }
                }
            }
        }
        return $icons;
    }

    public function getSuggestionsProperty()
    {
        return empty($this->customAbility) ? [] : 
            $this->abilityService->suggestAbilityCompletion($this->customAbility);
    }

    public function render()
    {
        return view('livewire.card-creator.components.ability-selector', [
            'categories' => $this->categories,
            'templatesByCategory' => $this->templatesByCategory,
            'categoryDescription' => $this->categoryDescription,
            'parsedAbilities' => $this->parsedAbilities,
            'abilityIcons' => $this->abilityIcons,
            'suggestions' => $this->suggestions
        ]);
    }
}

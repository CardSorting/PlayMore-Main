<?php

namespace App\Livewire\CardCreator\Components;

use Livewire\Component;
use App\Models\Gallery;
use App\Livewire\CardCreator\Services\CardTypeService;
use App\Livewire\CardCreator\Services\ManaSymbolService;
use App\Livewire\CardCreator\Services\AbilityService;
use App\Livewire\CardCreator\ViewModels\CardCreatorViewModel;
use App\Livewire\CardCreator\Traits\WithCardValidation;
use App\Livewire\CardCreator\Traits\WithCardState;
use App\Livewire\CardCreator\Traits\WithCardNotifications;

class CardCreator extends Component
{
    use WithCardValidation;
    use WithCardState;
    use WithCardNotifications;

    public Gallery $image;
    public $name = '';
    public $manaCost = '';
    public $cardType = '';
    public $abilities = '';
    public $flavorText = '';
    public $powerToughness = '';
    public $activeTypeCategory = 'Creatures';

    protected $cardTypeService;
    protected $manaSymbolService;
    protected $abilityService;
    protected $viewModel;

    protected $rules = [
        'name' => 'required|string|max:255',
        'manaCost' => 'required|string|max:50',
        'cardType' => 'required|string|max:255',
        'abilities' => 'required|string',
        'flavorText' => 'nullable|string',
        'powerToughness' => 'nullable|string|max:10',
    ];

    protected $listeners = [
        'manaCostUpdated' => 'updateManaCost',
        'cardTypeUpdated' => 'updateCardType',
        'abilitiesUpdated' => 'updateAbilities',
        'saveCard' => 'save',
        'resetCard' => 'resetState',
        'cardStateRestored' => 'handleStateRestored',
        'typeCategoryChanged' => 'updateTypeCategory'
    ];

    public function boot()
    {
        $this->cardTypeService = new CardTypeService();
        $this->manaSymbolService = new ManaSymbolService();
        $this->abilityService = new AbilityService();
    }

    public function mount(Gallery $image)
    {
        $this->image = $image;

        if ($this->hasState()) {
            $this->loadState();
            $this->notifyStateRestored();
        }

        $this->initializeViewModel();
    }

    protected function initializeViewModel()
    {
        $this->viewModel = new CardCreatorViewModel(
            $this->image,
            [
                'name' => $this->name,
                'manaCost' => $this->manaCost,
                'cardType' => $this->cardType,
                'abilities' => $this->abilities,
                'flavorText' => $this->flavorText,
                'powerToughness' => $this->powerToughness
            ],
            $this->cardTypeService,
            $this->manaSymbolService,
            $this->abilityService
        );
    }

    public function updatedName($value)
    {
        if ($this->validateCardName($value)) {
            $this->dispatch('cardNameUpdated', $value);
            $this->initializeViewModel();
        } else {
            $this->notifyValidationError('name');
        }
    }

    public function updateManaCost($value)
    {
        if ($this->manaSymbolService->validateManaCost($value)) {
            $this->manaCost = $value;
            $this->initializeViewModel();
        } else {
            $this->notifyInvalidFormat('mana cost', '{W}, {U}, {B}, {R}, {G}, or numbers');
        }
    }

    public function updateCardType($data)
    {
        if ($this->cardTypeService->validateType($data['type'])) {
            $this->cardType = $data['type'];
            if (!empty($data['subtypes'])) {
                $this->cardType .= ' - ' . implode(' ', $data['subtypes']);
            }
            $this->initializeViewModel();
        } else {
            $this->notifyInvalidOperation('Invalid card type selected');
        }
    }

    public function updateTypeCategory($category)
    {
        if (in_array($category, array_keys($this->cardTypeService->getCategories()))) {
            $this->activeTypeCategory = $category;
        }
    }

    public function updateAbilities($value)
    {
        if ($this->validateAbilities($value)) {
            $this->abilities = $value;
            $this->initializeViewModel();
        } else {
            $this->notifyValidationError('abilities');
        }
    }

    public function updatedFlavorText($value)
    {
        if ($this->validateFlavorText($value)) {
            $this->dispatch('flavorTextUpdated', $value);
            $this->initializeViewModel();
        } else {
            $this->notifyValidationError('flavorText');
        }
    }

    public function updatedPowerToughness($value)
    {
        if ($this->validatePowerToughness($value)) {
            $this->dispatch('powerToughnessUpdated', $value);
            $this->initializeViewModel();
        } else {
            $this->notifyInvalidFormat('power/toughness', 'N/N where N is a number or *');
        }
    }

    public function handleStateRestored($state)
    {
        $this->dispatch('cardStateRestored', $state);
        $this->initializeViewModel();
    }

    public function save()
    {
        try {
            if (!$this->validateAll()) {
                $this->notifyError('Please fix validation errors before saving');
                return;
            }

            $cardData = [
                'image_id' => $this->image->id,
                'name' => $this->name,
                'mana_cost' => $this->manaCost,
                'card_type' => $this->cardType,
                'abilities' => $this->viewModel->getCardPreviewData()['abilities'],
                'flavor_text' => $this->flavorText,
                'power_toughness' => $this->powerToughness,
                'color_identity' => $this->viewModel->getCardPreviewData()['colorIdentity'],
                'converted_mana_cost' => $this->viewModel->getCardPreviewData()['convertedManaCost']
            ];

            $this->clearState();
            $this->notifySuccessfulSave();

            return redirect()->route('cards.store', $cardData);
        } catch (\Exception $e) {
            $this->notifyFailedSave($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.card-creator.components.card-creator', [
            'viewModel' => $this->viewModel->toArray(),
            'activeTypeCategory' => $this->activeTypeCategory,
            'hasUnsavedChanges' => $this->isDirty()
        ]);
    }
}

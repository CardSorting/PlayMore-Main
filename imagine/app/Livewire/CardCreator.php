<?php

namespace App\Livewire;

use App\Models\Gallery;
use Livewire\Component;

class CardCreator extends Component
{
    public Gallery $image;
    
    // Form fields
    public $name = '';
    public $manaCost = '';
    public $cardType = '';
    public $abilities = '';
    public $flavorText = '';
    public $powerToughness = '';
    
    // UI state
    public $showManaSelector = false;
    public $showTypeSelector = false;
    public $showPTSelector = false;
    public $selectedAbilityTemplate = '';
    
    // Card type categories
    public $cardTypes = [
        'Creatures' => [
            'Creature' => ['Beings that battle', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z']
        ],
        'Spells' => [
            'Instant' => ['Cast anytime', 'M13 10V3L4 14h7v7l9-11h-7z'],
            'Sorcery' => ['Main phase only', 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z']
        ],
        'Permanents' => [
            'Enchantment' => ['Magical effects', 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
            'Artifact' => ['Magical items', 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z'],
            'Land' => ['Mana source', 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z']
        ]
    ];
    
    // Creature type categories
    public $creatureTypes = [
        'Humanoid' => [
            'Human', 'Elf', 'Goblin', 'Dwarf', 'Merfolk', 'Vampire'
        ],
        'Beast' => [
            'Beast', 'Wolf', 'Bear', 'Cat', 'Bird', 'Snake'
        ],
        'Mythical' => [
            'Dragon', 'Angel', 'Demon', 'Phoenix', 'Unicorn', 'Hydra'
        ],
        'Class' => [
            'Warrior', 'Wizard', 'Rogue', 'Cleric', 'Knight', 'Shaman'
        ],
        'Elemental' => [
            'Elemental', 'Spirit', 'Avatar', 'Djinn', 'Elemental Horror'
        ]
    ];
    
    // Ability templates by category
    public $abilityTemplates = [
        'Combat' => [
            'Flying' => ['Flying', 'This creature can only be blocked by creatures with flying.'],
            'First Strike' => ['First strike', 'This creature deals combat damage before creatures without first strike.'],
            'Double Strike' => ['Double strike', 'This creature deals both first-strike and regular combat damage.'],
            'Deathtouch' => ['Deathtouch', 'Any amount of damage this deals to a creature is enough to destroy it.'],
            'Trample' => ['Trample', 'This creature can deal excess combat damage to the player or planeswalker it\'s attacking.'],
            'Vigilance' => ['Vigilance', 'Attacking doesn\'t cause this creature to tap.'],
            'Haste' => ['Haste', 'This creature can attack and tap as soon as it comes under your control.']
        ],
        'Triggered' => [
            'ETB Draw' => ['When [CARDNAME] enters the battlefield, draw a card.', 'Triggers when the creature enters play'],
            'ETB Damage' => ['When [CARDNAME] enters the battlefield, it deals [X] damage to any target.', 'Deals damage on entry'],
            'Death Trigger' => ['When [CARDNAME] dies, draw a card.', 'Triggers when the creature dies'],
            'Attack Trigger' => ['Whenever [CARDNAME] attacks, draw a card.', 'Triggers when attacking']
        ],
        'Activated' => [
            'Tap Draw' => ['{T}: Draw a card.', 'Tap to draw a card'],
            'Pump' => ['{1}: [CARDNAME] gets +1/+1 until end of turn.', 'Pay mana to increase power and toughness'],
            'Sacrifice' => ['Sacrifice [CARDNAME]: Draw two cards.', 'Sacrifice for an effect']
        ],
        'Static' => [
            'Anthem' => ['Other creatures you control get +1/+1.', 'Continuous boost to other creatures'],
            'Cost Reduction' => ['Spells you cast cost {1} less to cast.', 'Makes your spells cheaper'],
            'Prevention' => ['Prevent all damage that would be dealt to [CARDNAME].', 'Damage prevention']
        ]
    ];

    public function mount(Gallery $image)
    {
        $this->image = $image;
    }

    // Mana symbol categories
    public $manaSymbols = [
        'Colors' => ['W', 'U', 'B', 'R', 'G'],
        'Colorless' => ['1', '2', '3', '4', '5'],
        'Hybrid' => ['W/U', 'U/B', 'B/R', 'R/G', 'G/W'],
        'Phyrexian' => ['W/P', 'U/P', 'B/P', 'R/P', 'G/P']
    ];

    public function addManaSymbol($symbol)
    {
        if (strlen($this->manaCost) < 15) { // Prevent overly long mana costs
            $this->manaCost .= $symbol;
        }
    }

    public function clearManaCost()
    {
        $this->manaCost = '';
        $this->dispatch('mana-cleared');
    }

    public function removeManaSymbol()
    {
        if (!empty($this->manaCost)) {
            // Remove the last symbol (handles both single characters and hybrid/phyrexian symbols)
            if (substr($this->manaCost, -2, 1) === '/') {
                $this->manaCost = substr($this->manaCost, 0, -3);
            } else {
                $this->manaCost = substr($this->manaCost, 0, -1);
            }
        }
    }

    public function selectCardType($baseType)
    {
        if ($baseType === 'Creature') {
            $this->showTypeSelector = true;
            $this->cardType = 'Creature - ';
        } else {
            $this->cardType = $baseType;
            $this->showTypeSelector = false;
            if ($baseType !== 'Creature') {
                $this->powerToughness = '';
            }
        }
    }

    public function addCreatureType($type)
    {
        if (str_starts_with($this->cardType, 'Creature - ')) {
            $this->cardType .= $type;
        }
        $this->showTypeSelector = false;
    }

    public function addAbilityTemplate($category, $key)
    {
        $template = $this->abilityTemplates[$category][$key][0];
        $ability = str_replace('[CARDNAME]', $this->name ?: 'This creature', $template);
        $ability = str_replace('[X]', '2', $ability); // Default X value
        
        if (!empty($this->abilities)) {
            $this->abilities .= "\n";
        }
        $this->abilities .= $ability;
        
        $this->dispatch('ability-added', [
            'ability' => $ability,
            'category' => $category
        ]);
    }

    public function getAbilityDescription($category, $key)
    {
        return $this->abilityTemplates[$category][$key][1];
    }

    public function setPowerToughness($power, $toughness)
    {
        $this->powerToughness = "{$power}/{$toughness}";
        $this->showPTSelector = false;
    }

    public function save()
    {
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'manaCost' => 'required|string|max:50',
            'cardType' => 'required|string|max:255',
            'abilities' => 'required|string',
            'flavorText' => 'nullable|string',
            'powerToughness' => 'nullable|string|max:10',
        ]);

        $data = array_merge($validatedData, [
            'image_id' => $this->image->id,
            'image_url' => $this->image->image_url,
        ]);

        return redirect()->route('cards.store', $data);
    }

    public function render()
    {
        return view('livewire.card-creator');
    }
}

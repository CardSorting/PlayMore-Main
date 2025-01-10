<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-xl font-bold text-gray-900">{{ $card['name'] }}</h3>
        <button wire:click="toggleDetails" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Card Image -->
        <div class="space-y-4">
            <div class="relative rounded-lg overflow-hidden shadow-lg">
                <img src="{{ $card['image_url'] }}" 
                     alt="{{ $card['name'] }}" 
                     class="w-full object-cover transform transition-all duration-500 hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 hover:opacity-100 transition-opacity"></div>
            </div>
            
            <!-- Mana Cost -->
            @if(isset($card['mana_cost']) && !empty($card['mana_cost']))
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-500">Mana Cost:</span>
                    <div class="flex space-x-1">
                        @foreach(explode(',', $card['mana_cost']) as $symbol)
                            @include('components.mana-symbol', ['symbol' => $symbol])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Card Details -->
        <div class="space-y-4">
            <!-- Card Type -->
            <div>
                <h4 class="text-sm font-medium text-gray-500">Card Type</h4>
                <p class="text-gray-900">{{ $card['card_type'] }}</p>
            </div>

            <!-- Abilities -->
            <div>
                <h4 class="text-sm font-medium text-gray-500">Abilities</h4>
                <div class="text-gray-900 space-y-2">
                    @if($this->hasAbilities())
                        @foreach($card['abilities_array'] as $ability)
                            <p class="py-1 px-2 bg-gray-50 rounded-md">{{ $ability }}</p>
                        @endforeach
                    @else
                        <p class="py-1 px-2 bg-gray-50 rounded-md">{{ $this->getAbilitiesDisplay() }}</p>
                    @endif
                </div>
            </div>

            <!-- Flavor Text -->
            @if($card['flavor_text'])
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Flavor Text</h4>
                    <p class="text-gray-900 italic">{{ $card['flavor_text'] }}</p>
                </div>
            @endif

            <!-- Card Stats -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Author</h4>
                    <p class="text-gray-900">{{ $card['author'] }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Rarity</h4>
                    <p class="text-gray-900 flex items-center space-x-2">
                        <span class="inline-block w-3 h-3 rounded-full
                            @if($this->isMythicRare()) bg-orange-400
                            @elseif($this->isRare()) bg-yellow-300
                            @elseif($this->isUncommon()) bg-gray-400
                            @else bg-gray-600 @endif">
                        </span>
                        <span>{{ $card['rarity'] }}</span>
                    </p>
                </div>
                @if($card['power_toughness'])
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Power/Toughness</h4>
                        <p class="text-gray-900">{{ $card['power_toughness'] }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

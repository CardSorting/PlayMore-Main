<div class="relative group" wire:key="card-{{ $card['name'] }}" role="article" aria-label="Magic Card: {{ $card['name'] }}">
    <div class="mtg-card bg-white overflow-hidden shadow-xl rounded-lg p-4 transform transition-all duration-700 ease-out
                {{ $showFlipAnimation ? 'rotate-y-180 scale-105' : '' }} 
                hover:shadow-[0_0_40px_rgba(255,215,0,0.4)]
                {{ $card['rarity'] === 'Mythic Rare' ? 'mythic-rare-card' : '' }}
                {{ $card['rarity'] === 'Rare' ? 'rare-card' : '' }}"
         data-rarity="{{ $card['rarity'] }}"
         style="transform-style: preserve-3d; perspective: 1000px;">
        
        <!-- Front of Card -->
        <div class="w-full aspect-[2.5/3.5] relative rounded-lg overflow-hidden transition-all duration-500
                    {{ $showFlipAnimation ? 'opacity-0 rotate-y-180' : 'opacity-100' }}"
             style="backface-visibility: hidden;">
            
            <!-- Enhanced Holographic Overlay -->
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-all duration-500 pointer-events-none z-10
                        {{ $card['rarity'] === 'Mythic Rare' ? 'mythic-holographic' : '' }}
                        {{ $card['rarity'] === 'Rare' ? 'rare-holographic' : '' }}">
            </div>
            
            <div class="card-frame h-full flex flex-col bg-[#f8e7c9] border-8 border-[#171314] rounded-lg overflow-hidden relative">
    <!-- Card Frame Texture -->
    <div class="absolute inset-0 mix-blend-overlay opacity-30 pointer-events-none" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1IiBoZWlnaHQ9IjUiPgo8cmVjdCB3aWR0aD0iNSIgaGVpZ2h0PSI1IiBmaWxsPSIjZmZmIj48L3JlY3Q+CjxwYXRoIGQ9Ik0wIDVMNSAwWk02IDRMNCA2Wk0tMSAxTDEgLTFaIiBzdHJva2U9IiMyOTI1MjQiIHN0cm9rZS1vcGFjaXR5PSIwLjA1Ij48L3BhdGg+Cjwvc3ZnPg==');"></div>
                <!-- Title Bar with Enhanced Styling -->
                <div class="card-title-bar relative flex justify-between items-center px-3 py-2 bg-[#171314] text-[#d3ced9]">
                    <div class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                    <h2 class="card-name text-base font-bold font-matrix tracking-wide">
                        {{ $card['name'] }}
                    </h2>
                    <div class="mana-cost flex space-x-1">
                        @if(isset($card['mana_cost']))
                            @foreach(explode(',', $card['mana_cost']) as $symbol)
                                <div class="mana-symbol rounded-full flex justify-center items-center text-xs font-bold w-5 h-5 transform transition-transform duration-200 relative
                                    @if(strtolower($symbol) == 'w') bg-[#f8e7c9] text-black border border-black
                                    @elseif(strtolower($symbol) == 'u') bg-[#f8e7c9] text-black border border-black
                                    @elseif(strtolower($symbol) == 'b') bg-[#f8e7c9] text-black border border-black
                                    @elseif(strtolower($symbol) == 'r') bg-[#f8e7c9] text-black border border-black
                                    @elseif(strtolower($symbol) == 'g') bg-[#f8e7c9] text-black border border-black
                                    @else bg-[#f8e7c9] text-black border border-black
                                    @endif">
                                    <div class="absolute inset-0 rounded-full bg-white opacity-20 mix-blend-overlay"></div>
                                    {{ $symbol }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Art Box -->
                <div class="relative mx-2 mt-2 mb-2 overflow-hidden group h-[45%]">
                    <div class="absolute inset-0 border border-[#171314] z-20 pointer-events-none"></div>
                    <img src="{{ $card['image_url'] }}" 
                         alt="{{ $card['name'] }}" 
                         class="w-full h-[180px] object-cover object-center transform transition-all duration-700 ease-out
                                group-hover:scale-110 group-hover:filter group-hover:brightness-110"
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-white/10 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                    @if($card['rarity'] === 'Mythic Rare' || $card['rarity'] === 'Rare')
                        <div class="absolute inset-0 bg-[conic-gradient(from_0deg,transparent_0deg,rgba(255,255,255,0.2)_90deg,transparent_180deg)] animate-rotate-slow opacity-0 group-hover:opacity-100"></div>
                    @endif
                </div>

                <!-- Type Line with Enhanced Border -->
                <div class="card-type relative mx-2 mb-2">
                    <div class="absolute inset-0 bg-[#171314]"></div>
                    <div class="relative px-3 py-1 text-sm font-matrix bg-[#f8e7c9] text-[#171314] tracking-wide border-t border-b border-[#171314]">
                    {{ $card['card_type'] }}
                </div>

                <!-- Text Box -->
                <div class="card-text relative mx-2 h-[30%] bg-[#f8e7c9] overflow-hidden border border-[#171314] text-[#171314]">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/20 via-transparent to-black/5"></div>
                    <div class="absolute inset-0 border border-white/10"></div>
                    <div class="relative p-4 flex flex-col h-full">
                        <div class="flex-grow overflow-y-auto space-y-2 scrollbar-thin scrollbar-thumb-[#171314]/20 scrollbar-track-transparent">
                            <p class="abilities-text text-sm font-matrix leading-6 text-[#171314]">{{ $card['abilities'] }}</p>
                            <div class="divider h-px bg-gradient-to-r from-transparent via-[#171314]/20 to-transparent my-2"></div>
                            <p class="flavor-text italic text-sm font-mplantin leading-6 text-[#171314]/90">{{ $card['flavor_text'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Info Line -->
                <div class="card-footer relative flex justify-between items-center mt-2 mx-2 mb-2 px-3 py-1.5 bg-[#171314] text-[#d3ced9] text-xs font-matrix tracking-wide">
                    <div class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                    <div class="relative flex justify-between items-center w-full z-10">
                        <div class="flex items-center space-x-2">
                            <span class="rarity-symbol text-xs
                                @if($card['rarity'] == 'Mythic Rare') text-orange-400
                                @elseif($card['rarity'] == 'Rare') text-yellow-300
                                @elseif($card['rarity'] == 'Uncommon') text-gray-400
                                @else text-gray-600 @endif">
                                @if($card['rarity'] == 'Mythic Rare') M
                                @elseif($card['rarity'] == 'Rare') R
                                @elseif($card['rarity'] == 'Uncommon') U
                                @else C @endif
                            </span>
                            <span class="rarity-details font-medium tracking-wide">{{ $card['rarity'] }}</span>
                        </div>
                        @if($card['power_toughness'])
                            <span class="power-toughness font-bold">
                                {{ $card['power_toughness'] }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

        <!-- Back of Card -->
        <div class="absolute inset-0 w-full aspect-[2.5/3.5] rounded-lg overflow-hidden transition-all duration-500
                    {{ $showFlipAnimation ? 'opacity-100 rotate-y-0' : 'opacity-0 rotate-y-180' }}"
             style="backface-visibility: hidden;">
            <div class="card-back h-full bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-900 p-0.5">
                <div class="h-full bg-gradient-to-br from-indigo-800 via-purple-800 to-indigo-800 rounded-lg p-4">
                    <!-- Decorative Pattern -->
                    <div class="relative h-full border-4 border-gold-500 rounded-lg overflow-hidden">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(255,215,0,0.1),transparent)]"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-32 h-32 bg-gradient-to-r from-gold-400 via-gold-200 to-gold-400 rounded-full opacity-20"></div>
                        </div>
                        <!-- Card Info -->
                        <div class="relative h-full flex flex-col items-center justify-center text-center p-6 text-gold-200">
                            <h3 class="text-xl font-bold mb-4">{{ $card['name'] }}</h3>
                            <p class="text-sm mb-4">{{ $card['card_type'] }}</p>
                            <p class="text-xs italic">{{ $card['rarity'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Menu -->
        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20">
            <div class="flex space-x-2">
                <button wire:click="toggleDetails" 
                        class="bg-gray-800 text-white p-2 rounded-full hover:bg-gray-700 transition-colors duration-200 shadow-lg"
                        title="View Details">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
                <button wire:click="flipCard" 
                        class="bg-purple-600 text-white p-2 rounded-full hover:bg-purple-500 transition-colors duration-200 shadow-lg"
                        title="Flip Card">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    @if($showDetails)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click.self="toggleDetails">
        <div class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95 hover:scale-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">{{ $card['name'] }}</h3>
                <button wire:click="toggleDetails" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <img src="{{ $card['image_url'] }}" alt="{{ $card['name'] }}" class="w-full rounded-lg shadow-lg">
                </div>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Card Type</h4>
                        <p class="text-gray-900">{{ $card['card_type'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Abilities</h4>
                        <p class="text-gray-900">{{ $card['abilities'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Flavor Text</h4>
                        <p class="text-gray-900 italic">{{ $card['flavor_text'] }}</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Rarity</h4>
                            <p class="text-gray-900">{{ $card['rarity'] }}</p>
                        </div>
                        @if(isset($card['power_toughness']))
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Power/Toughness</h4>
                            <p class="text-gray-900">{{ $card['power_toughness'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

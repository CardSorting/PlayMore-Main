<div class="relative group" wire:key="card-{{ $card['name'] }}" role="article" aria-label="Magic Card: {{ $card['name'] }}">
    <div class="bg-white overflow-hidden shadow-xl rounded-lg p-4 transform transition-all duration-700 ease-out
                {{ $showFlipAnimation ? 'rotate-y-180 scale-105' : '' }} 
                hover:shadow-[0_0_40px_rgba(255,215,0,0.4)]
                {{ $card['rarity'] === 'Mythic Rare' ? 'mythic-rare-card' : '' }}
                {{ $card['rarity'] === 'Rare' ? 'rare-card' : '' }}"
         style="transform-style: preserve-3d; perspective: 1000px;">
        
        <!-- Front of Card -->
        <div class="mtg-card w-full aspect-[2.5/3.5] relative rounded-lg overflow-hidden transition-all duration-500
                    {{ $showFlipAnimation ? 'opacity-0 rotate-y-180' : 'opacity-100' }}"
             data-rarity="{{ $card['rarity'] }}"
             style="backface-visibility: hidden;">
            
            <!-- Enhanced Holographic Overlay -->
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-all duration-500 pointer-events-none z-10
                        {{ $card['rarity'] === 'Mythic Rare' ? 'mythic-holographic' : '' }}
                        {{ $card['rarity'] === 'Rare' ? 'rare-holographic' : '' }}">
            </div>
            
            <div class="card-frame h-full p-3 flex flex-col bg-gradient-to-b from-[#f8f8f8] to-[#e8e8e8] border border-gray-300">
                <!-- Enhanced Card Header -->
                <div class="card-header flex justify-between items-center p-2 rounded-lg mb-1 shadow-sm border border-gray-200
                            {{ $card['rarity'] === 'Mythic Rare' ? 'bg-gradient-to-r from-orange-100 via-amber-200 to-orange-100' : '' }}
                            {{ $card['rarity'] === 'Rare' ? 'bg-gradient-to-r from-yellow-100 via-amber-100 to-yellow-100' : '' }}
                            {{ $card['rarity'] === 'Uncommon' ? 'bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200' : '' }}
                            {{ $card['rarity'] === 'Common' ? 'bg-gradient-to-r from-[#e9e5cd] to-[#f5f1e6]' : '' }}">
                    <h2 class="card-name text-lg font-bold" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                        {{ $card['name'] }}
                    </h2>
                    <div class="mana-cost flex space-x-1">
                        @if(isset($card['mana_cost']))
                            @foreach(explode(',', $card['mana_cost']) as $symbol)
                                <div class="mana-symbol rounded-full flex justify-center items-center text-xs font-bold w-6 h-6 shadow-lg transform hover:scale-110 transition-transform duration-200
                                    @if(strtolower($symbol) == 'w') bg-gradient-to-br from-yellow-100 to-yellow-300 text-black border-2 border-yellow-400
                                    @elseif(strtolower($symbol) == 'u') bg-gradient-to-br from-blue-400 to-blue-600 text-white border-2 border-blue-300
                                    @elseif(strtolower($symbol) == 'b') bg-gradient-to-br from-gray-800 to-black text-white border-2 border-gray-600
                                    @elseif(strtolower($symbol) == 'r') bg-gradient-to-br from-red-400 to-red-600 text-white border-2 border-red-300
                                    @elseif(strtolower($symbol) == 'g') bg-gradient-to-br from-green-400 to-green-600 text-white border-2 border-green-300
                                    @else bg-gradient-to-br from-gray-300 to-gray-500 text-black border-2 border-gray-400
                                    @endif">
                                    {{ $symbol }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Enhanced Card Image with Advanced Hover Effect -->
                <div class="relative rounded-lg overflow-hidden mb-1 shadow-lg group">
                    <img src="{{ $card['image_url'] }}" 
                         alt="{{ $card['name'] }}" 
                         class="w-full h-[140px] object-cover object-center transform transition-all duration-700 ease-out
                                group-hover:scale-110 group-hover:filter group-hover:brightness-110"
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-white/10 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                    @if($card['rarity'] === 'Mythic Rare' || $card['rarity'] === 'Rare')
                        <div class="absolute inset-0 bg-[conic-gradient(from_0deg,transparent_0deg,rgba(255,255,255,0.2)_90deg,transparent_180deg)] animate-rotate-slow opacity-0 group-hover:opacity-100"></div>
                    @endif
                </div>

                <!-- Card Type -->
                <div class="card-type bg-gradient-to-r from-[#e9e5cd] to-[#f5f1e6] p-1.5 text-xs border border-gray-200 rounded-md mb-1 font-semibold shadow-sm">
                    {{ $card['card_type'] }}
                </div>

                <!-- Card Text -->
                <div class="card-text bg-[#f5f1e6] rounded-lg flex-grow overflow-y-auto p-2 shadow-inner border border-gray-200 text-xs">
                    <p class="abilities-text mb-2">{{ $card['abilities'] }}</p>
                    <div class="divider h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent my-1"></div>
                    <p class="flavor-text mt-1 italic text-gray-700" style="font-family: 'Crimson Text', serif;">
                        {{ $card['flavor_text'] }}
                    </p>
                </div>

                <!-- Footer -->
                <div class="card-footer flex justify-between items-center mt-1 bg-gradient-to-r from-gray-800 to-gray-700 p-1.5 rounded-md text-white text-xs shadow-lg">
                    <div class="flex items-center space-x-2">
                        <span class="rarity-indicator w-2 h-2 rounded-full
                            @if($card['rarity'] == 'Mythic Rare') bg-orange-400
                            @elseif($card['rarity'] == 'Rare') bg-yellow-300
                            @elseif($card['rarity'] == 'Uncommon') bg-gray-400
                            @else bg-gray-600 @endif">
                        </span>
                        <span class="rarity-details font-medium tracking-wide">
                            {{ $card['rarity'] }}
                        </span>
                    </div>
                    @if($card['power_toughness'])
                        <span class="power-toughness bg-gray-900 px-3 py-1 rounded-full font-bold shadow-inner">
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

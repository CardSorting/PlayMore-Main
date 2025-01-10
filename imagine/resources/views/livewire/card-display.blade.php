<div class="relative group h-full" wire:key="card-{{ $card['name'] }}">
    <div class="mtg-card h-full bg-white overflow-hidden shadow-xl rounded-lg transform transition-all duration-700 ease-out
                {{ $showFlipAnimation ? 'rotate-y-180 scale-105' : '' }} 
                {{ $this->getRarityClasses() }}"
         data-rarity="{{ $this->getNormalizedRarity() }}"
         data-author="{{ $card['author'] }}"
         style="transform-style: preserve-3d; will-change: transform;">
        
        <!-- Front of Card -->
        <div class="w-full h-full relative rounded-lg overflow-hidden transition-all duration-500
                    {{ $showFlipAnimation ? 'opacity-0 rotate-y-180' : 'opacity-100' }}"
             style="backface-visibility: hidden;">
            
            <div class="card-frame h-full flex flex-col bg-[#f8e7c9] border-[14px] border-[#171314] rounded-lg overflow-hidden relative
                        before:absolute before:inset-0 before:bg-[radial-gradient(circle_at_50%_50%,rgba(255,255,255,0.25),transparent_70%)] before:mix-blend-overlay
                        after:absolute after:inset-0 after:bg-[linear-gradient(45deg,transparent,rgba(255,255,255,0.15),transparent)] after:mix-blend-overlay">
                <!-- Card Frame Elements -->
                <x-card-frame-elements />
                
                <!-- Title Bar -->
                <div class="card-title-bar relative flex justify-between items-center px-4 py-3 bg-[#171314] text-[#d3ced9]">
                    <x-card-title-bar />
                    <h2 class="card-name text-base font-bold font-matrix tracking-wide">
                        {{ $card['name'] }}
                    </h2>
                    <div class="mana-cost flex space-x-1">
                        @if(isset($card['mana_cost']))
                            @foreach(explode(',', $card['mana_cost']) as $symbol)
                                <x-mana-symbol :symbol="$symbol" />
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Art Box -->
                <div class="relative mx-2 mt-2 mb-2 overflow-hidden group h-[180px]">
                    <x-card-art-box />
                    <img src="{{ $card['image_url'] }}" 
                         alt="{{ $card['name'] }}" 
                         class="w-full h-full object-cover object-center transform transition-all duration-700 ease-out
                                group-hover:scale-110 group-hover:filter group-hover:brightness-110"
                         loading="lazy">
                </div>

                <!-- Type Line -->
                <div class="card-type relative mx-2 mb-2">
                    <x-card-type-line :type="$card['card_type']" />
                </div>

                <!-- Text Box -->
                <div class="card-text relative mx-2 bg-[#f8e7c9] border-2 border-[#171314] text-[#171314] min-h-[120px] flex-grow rounded-sm">
                    <x-card-text-box 
                        :abilities="$this->hasAbilities() ? $card['abilities_array'] : $this->getAbilitiesDisplay()"
                        :flavor-text="$card['flavor_text']" />
                </div>

                <!-- Info Line -->
                <div class="card-footer relative flex justify-between items-center mt-2 mx-2 mb-2 px-4 py-2 bg-[#171314] text-[#d3ced9] text-xs font-matrix tracking-wide">
                    <x-card-info-line 
                        :rarity="$card['rarity']"
                        :power-toughness="$card['power_toughness']" />
                </div>
            </div>
        </div>

        <!-- Back of Card -->
        <div class="absolute inset-0 w-full h-full rounded-lg overflow-hidden transition-all duration-500
                    {{ $showFlipAnimation ? 'opacity-100 rotate-y-0' : 'opacity-0 rotate-y-180' }}"
             style="backface-visibility: hidden;">
            <x-card-back :card="$card" />
        </div>

        <!-- Quick Actions Menu -->
        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20">
            <div class="flex space-x-2">
                <!-- Info Button -->
                <button wire:click="showDetails" 
                        class="bg-gray-800/90 text-white p-2 rounded-full hover:bg-gray-700 transition-all duration-200 shadow-lg
                               transform hover:scale-110 hover:rotate-12
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="sr-only">Show Details</span>
                </button>

                <!-- Flip Button -->
                <button wire:click="flipCard" 
                        class="bg-purple-600/90 text-white p-2 rounded-full hover:bg-purple-500 transition-all duration-200 shadow-lg
                               transform hover:scale-110 hover:-rotate-12
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500
                               {{ $showFlipAnimation ? 'rotate-180' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span class="sr-only">Flip Card</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Card Details Modal -->
    <livewire:card-details-modal />
</div>

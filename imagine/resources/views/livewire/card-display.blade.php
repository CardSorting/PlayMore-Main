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
                @include('components.card-frame-elements')
                
                <!-- Title Bar -->
                <div class="card-title-bar relative flex justify-between items-center px-4 py-3 bg-[#171314] text-[#d3ced9]">
                    @include('components.card-title-bar')
                    <h2 class="card-name text-base font-bold font-matrix tracking-wide">
                        {{ $card['name'] }}
                    </h2>
                    <div class="mana-cost flex space-x-1">
                        @if(isset($card['mana_cost']))
                            @foreach(explode(',', $card['mana_cost']) as $symbol)
                                @include('components.mana-symbol', ['symbol' => $symbol])
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Art Box -->
                <div class="relative mx-2 mt-2 mb-2 overflow-hidden group h-[180px]">
                    @include('components.card-art-box')
                    <img src="{{ $card['image_url'] }}" 
                         alt="{{ $card['name'] }}" 
                         class="w-full h-full object-cover object-center transform transition-all duration-700 ease-out
                                group-hover:scale-110 group-hover:filter group-hover:brightness-110"
                         loading="lazy">
                </div>

                <!-- Type Line -->
                <div class="card-type relative mx-2 mb-2">
                    @include('components.card-type-line')
                    <div class="relative px-4 py-1.5 text-sm font-matrix bg-[#f8e7c9] text-[#171314] tracking-wide">
                        {{ $card['card_type'] }}
                    </div>
                </div>

                <!-- Text Box -->
                <div class="card-text relative mx-2 bg-[#f8e7c9] border-2 border-[#171314] text-[#171314] min-h-[120px] flex-grow rounded-sm">
                    @include('components.card-text-box')
                    <div class="relative p-4">
                        <div class="space-y-2">
                            <div class="abilities-text text-sm font-matrix leading-6 text-[#171314]">
                                @if($this->hasAbilities())
                                    @foreach($card['abilities_array'] as $ability)
                                        <p class="mb-2">{{ $ability }}</p>
                                    @endforeach
                                @else
                                    <p>{{ $this->getAbilitiesDisplay() }}</p>
                                @endif
                            </div>
                            @if($card['flavor_text'])
                                <div class="divider h-px bg-gradient-to-r from-transparent via-[#171314]/20 to-transparent my-2"></div>
                                <p class="flavor-text italic text-sm font-mplantin leading-6 text-[#171314]/90">{{ $card['flavor_text'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Info Line -->
                <div class="card-footer relative flex justify-between items-center mt-2 mx-2 mb-2 px-4 py-2 bg-[#171314] text-[#d3ced9] text-xs font-matrix tracking-wide">
                    @include('components.card-info-line')
                    <div class="relative flex justify-between items-center w-full z-10">
                        <div class="flex items-center space-x-2">
                            <span class="rarity-symbol text-xs {{ $this->getRarityClasses() }}">
                                {{ $this->getNormalizedRarity() }}
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
        </div>

        <!-- Back of Card -->
        <div class="absolute inset-0 w-full h-full rounded-lg overflow-hidden transition-all duration-500
                    {{ $showFlipAnimation ? 'opacity-100 rotate-y-0' : 'opacity-0 rotate-y-180' }}"
             style="backface-visibility: hidden;">
            @include('components.card-back')
        </div>

        <!-- Quick Actions Menu -->
        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20">
            @include('components.card-actions')
        </div>
    </div>

    <!-- Details Modal -->
    @if($showDetails)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click.self="toggleDetails">
            <div class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95 hover:scale-100">
                @include('components.card-details-modal')
            </div>
        </div>
    @endif
</div>

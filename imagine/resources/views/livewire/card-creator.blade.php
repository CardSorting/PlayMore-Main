<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Card Preview -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <!-- Original Image Author -->
        <div class="mb-4 text-sm text-gray-600">
            <span class="font-medium">Original Image by:</span>
            <span class="ml-1">{{ $image->user->name }}</span>
        </div>
        <div class="mt-8">
            <div class="mtg-card w-[375px] h-[525px] mx-auto relative rounded-[18px] shadow-lg overflow-hidden bg-white">
                <!-- Card Preview Content (unchanged) -->
            </div>
        </div>
    </div>

    <!-- Visual Card Creator -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <form wire:submit="save" class="space-y-6">
            <!-- Card Name -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-semibold text-gray-900">Card Name</h3>
                </div>
                <div>
                    <x-text-input wire:model.live="name" id="name" type="text" class="block w-full" placeholder="Enter card name" required />
                    @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Mana Cost -->
            <div x-data="{ activeTab: 'Colors' }" class="space-y-4 pt-2">
                <!-- Mana Cost Content (unchanged) -->
            </div>

            <!-- Card Type -->
            <div x-data="{ activeTypeCategory: 'Creatures' }" class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-semibold text-gray-900">Card Type</h3>
                </div>
                
                <!-- Card Type Categories -->
                <div class="flex space-x-2">
                    @foreach(array_keys($cardTypes) as $category)
                        <button type="button"
                            @click="activeTypeCategory = '{{ $category }}'"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors"
                            :class="activeTypeCategory === '{{ $category }}' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                            {{ $category }}
                        </button>
                    @endforeach
                </div>

                <!-- Card Types -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    @foreach($cardTypes as $category => $types)
                        <div x-show="activeTypeCategory === '{{ $category }}'"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="grid grid-cols-2 gap-4">
                            @foreach($types as $type => $details)
                                <button type="button" 
                                    wire:click="selectCardType('{{ $type }}')"
                                    class="p-4 text-center rounded-lg transition-colors {{ $cardType === $type || (str_starts_with($cardType, $type . ' -')) ? 'bg-gray-800 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-100' }}">
                                    <svg class="w-8 h-8 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $details[1] }}" />
                                    </svg>
                                    <div class="font-medium">{{ $type }}</div>
                                    <div class="text-xs mt-1 {{ $cardType === $type || (str_starts_with($cardType, $type . ' -')) ? 'text-gray-300' : 'text-gray-500' }}">
                                        {{ $details[0] }}
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <!-- Creature Types -->
                @if($showTypeSelector)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Select Creature Type</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($creatureTypes as $category => $types)
                                <div class="relative group">
                                    <button type="button"
                                        class="w-full px-3 py-2 text-sm font-medium rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition-colors">
                                        {{ $category }}
                                        <svg class="w-4 h-4 inline-block ml-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 invisible group-hover:visible z-10">
                                        <div class="p-2 space-y-1">
                                            @foreach($types as $type)
                                                <button type="button"
                                                    wire:click="addCreatureType('{{ $type }}')"
                                                    class="block w-full text-left px-3 py-2 text-sm rounded-lg hover:bg-gray-100 transition-colors">
                                                    {{ $type }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @error('cardType') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Abilities -->
            <div x-data="{ activeCategory: 'Combat' }" class="space-y-4">
                <!-- Abilities Content (unchanged) -->
            </div>

            <!-- Flavor Text -->
            <div class="space-y-4">
                <!-- Flavor Text Content (unchanged) -->
            </div>

            <!-- Power/Toughness -->
            @if(str_starts_with($cardType, 'Creature'))
                <div class="space-y-4">
                    <!-- Power/Toughness Content (unchanged) -->
                </div>
            @endif

            <!-- Submit Button -->
            <div class="flex justify-end pt-6 mt-6 border-t border-gray-200">
                <x-primary-button class="px-6 py-3 text-base !text-white !bg-gray-800 hover:!bg-gray-700">
                    {{ __('Create Card') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

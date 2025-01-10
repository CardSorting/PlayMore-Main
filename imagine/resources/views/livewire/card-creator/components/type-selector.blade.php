<div class="space-y-6">
    <!-- Card Types Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        @foreach($selector['typesByCategory'][$activeCategory] as $type => $details)
            <button type="button"
                wire:click="selectCardType('{{ $type }}')"
                class="relative group p-4 bg-white border rounded-lg transition-colors {{ $selector['currentType'] === $type ? 'border-gray-800 bg-gray-50' : 'border-gray-200 hover:bg-gray-50' }}">
                <div class="flex flex-col items-center text-center space-y-2">
                    @if($details['icon'])
                        <div class="w-8 h-8 flex items-center justify-center">
                            <x-dynamic-component :component="$details['icon']" class="w-6 h-6" />
                        </div>
                    @endif
                    <span class="text-sm font-medium {{ $selector['currentType'] === $type ? 'text-gray-900' : 'text-gray-700' }}">
                        {{ $type }}
                    </span>
                </div>
                @if($details['description'])
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                        {{ $details['description'] }}
                    </div>
                @endif
            </button>
        @endforeach
    </div>

    <!-- Creature Type Selector -->
    @if($selector['showTypeSelector'])
        <div class="border-t border-gray-200 pt-6 space-y-4">
            <div class="flex items-center justify-between">
                <h4 class="text-base font-medium text-gray-900">Creature Type</h4>
                @if(!empty($selector['selectedSubtypes']))
                    <button type="button"
                        wire:click="clearSubtypes"
                        class="text-sm text-gray-500 hover:text-gray-700">
                        Clear
                    </button>
                @endif
            </div>

            <!-- Selected Types -->
            @if(!empty($selector['selectedSubtypes']))
                <div class="flex flex-wrap gap-2">
                    @foreach($selector['selectedSubtypes'] as $subtype)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100">
                            {{ $subtype }}
                            <button type="button"
                                wire:click="removeCreatureType('{{ $subtype }}')"
                                class="ml-2 text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </span>
                    @endforeach
                </div>
            @endif

            <!-- Creature Type Categories -->
            <div x-data="{ activeTab: '{{ array_key_first($selector['creatureTypes']) }}' }" class="space-y-4">
                <div class="flex space-x-2 overflow-x-auto pb-2">
                    @foreach(array_keys($selector['creatureTypes']) as $category)
                        <button type="button"
                            @click="activeTab = '{{ $category }}'"
                            class="px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap transition-colors"
                            :class="{
                                'bg-gray-800 text-white': activeTab === '{{ $category }}',
                                'bg-gray-100 text-gray-700 hover:bg-gray-200': activeTab !== '{{ $category }}'
                            }">
                            {{ $category }}
                        </button>
                    @endforeach
                </div>

                <!-- Type Grid -->
                @foreach($selector['creatureTypes'] as $category => $types)
                    <div x-show="activeTab === '{{ $category }}'"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                        @foreach($types as $type)
                            <button type="button"
                                wire:click="addCreatureType('{{ $type }}')"
                                class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ in_array($type, $selector['selectedSubtypes']) ? 'bg-gray-800 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                                {{ $type }}
                            </button>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @error('cardType')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

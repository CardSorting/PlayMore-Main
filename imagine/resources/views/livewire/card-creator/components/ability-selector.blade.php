<div class="space-y-6">
    <div class="border-b border-gray-200 pb-2">
        <h3 class="text-lg font-semibold text-gray-900">Card Abilities</h3>
    </div>

    <!-- Current Abilities Preview -->
    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
        <div class="space-y-4">
            @forelse($selector['parsedAbilities'] as $index => $ability)
                <div class="flex items-start justify-between group">
                    <div class="flex items-start space-x-3">
                        @if(isset($selector['abilityIcons'][$index]))
                            <div class="w-5 h-5 mt-0.5 flex-shrink-0">
                                <x-dynamic-component 
                                    :component="$selector['abilityIcons'][$index]"
                                    class="w-5 h-5 text-gray-500" />
                            </div>
                        @endif
                        <p class="text-sm text-gray-700">{{ $ability }}</p>
                    </div>
                    <button type="button"
                        wire:click="removeAbility({{ $index }})"
                        class="p-1 text-gray-400 opacity-0 group-hover:opacity-100 hover:text-gray-500 focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @empty
                <p class="text-sm text-gray-500 text-center py-2">No abilities added yet</p>
            @endforelse
        </div>

        @if(!empty($selector['parsedAbilities']))
            <div class="mt-4 pt-4 border-t border-gray-200">
                <button type="button"
                    wire:click="clearAbilities"
                    class="text-sm text-gray-500 hover:text-gray-700">
                    Clear all abilities
                </button>
            </div>
        @endif
    </div>

    <!-- Ability Categories -->
    <div x-data="{ activeTab: '{{ $selector['categories'][0] ?? '' }}' }" class="space-y-4">
        <!-- Category Tabs -->
        <div class="flex space-x-2 overflow-x-auto pb-2">
            @foreach($selector['categories'] as $category)
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

        <!-- Ability Templates -->
        @foreach($selector['categories'] as $category)
            <div x-show="activeTab === '{{ $category }}'"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($selector['templatesByCategory'][$category] as $key => $template)
                    <button type="button"
                        wire:click="addAbilityTemplate('{{ $category }}', '{{ $key }}')"
                        class="relative group p-4 text-left bg-white border border-gray-200 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <div class="flex items-start space-x-3">
                            @if($template['icon'])
                                <div class="w-5 h-5 mt-0.5 flex-shrink-0">
                                    <x-dynamic-component 
                                        :component="$template['icon']"
                                        class="w-5 h-5 text-gray-500" />
                                </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-900">{{ $template['text'] }}</p>
                                @if($template['description'])
                                    <p class="mt-1 text-xs text-gray-500">{{ $template['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- Custom Ability Input -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h4 class="text-base font-medium text-gray-900">Custom Ability</h4>
            <button type="button"
                wire:click="addCustomAbility"
                class="px-3 py-1 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Add
            </button>
        </div>

        <div class="relative">
            <textarea
                wire:model.live="customAbility"
                rows="2"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                placeholder="Enter custom ability text..."></textarea>

            <!-- Ability Suggestions -->
            @if(!empty($selector['suggestions']))
                <div class="absolute z-10 w-full mt-1 bg-white rounded-lg shadow-lg border border-gray-200">
                    @foreach($selector['suggestions'] as $suggestion)
                        <button type="button"
                            wire:click="useAbilitySuggestion('{{ $suggestion['text'] }}')"
                            class="w-full px-4 py-2 text-sm text-left hover:bg-gray-50 focus:outline-none first:rounded-t-lg last:rounded-b-lg">
                            {{ $suggestion['text'] }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <p class="text-xs text-gray-500">
            Use [CARDNAME] to reference the card's name in the ability text.
            Mana symbols should be wrapped in curly braces (e.g., {W}, {2}).
        </p>
    </div>

    <!-- Error Message -->
    @error('abilities')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

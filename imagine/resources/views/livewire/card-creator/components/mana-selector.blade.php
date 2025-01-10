<div class="space-y-4">
    <div class="border-b border-gray-200 pb-2">
        <h3 class="text-lg font-semibold text-gray-900">Mana Cost</h3>
    </div>

    <!-- Current Mana Cost Preview -->
    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                @forelse($selector['currentManaCost'] as $symbol)
                    <x-mana-symbol :symbol="$symbol" class="w-6 h-6" />
                @empty
                    <span class="text-gray-400 text-sm">No mana cost</span>
                @endforelse
            </div>
            <div class="flex items-center space-x-2">
                <button type="button"
                    wire:click="removeLastSymbol"
                    class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"/>
                    </svg>
                </button>
                <button type="button"
                    wire:click="clearManaCost"
                    class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>
        @if($selector['maxLength'] - strlen($selector['currentManaCost']) <= 3)
            <div class="mt-2 text-xs text-yellow-600">
                {{ $selector['maxLength'] - strlen($selector['currentManaCost']) }} symbols remaining
            </div>
        @endif
    </div>

    <!-- Mana Symbol Categories -->
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

        <!-- Symbol Grid -->
        @foreach($selector['categories'] as $category)
            <div x-show="activeTab === '{{ $category }}'"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="grid grid-cols-5 gap-3">
                @foreach($selector['symbolsByCategory'][$category] as $symbol => $details)
                    <button type="button"
                        wire:click="addSymbol('{{ $symbol }}')"
                        class="relative group p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                        :disabled="strlen('{{ $selector['currentManaCost'] }}') >= {{ $selector['maxLength'] }}">
                        <div class="flex justify-center">
                            <x-mana-symbol :symbol="$symbol" class="w-8 h-8" />
                        </div>
                        @if($details['description'])
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                {{ $details['description'] }}
                            </div>
                        @endif
                    </button>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- Error Message -->
    @error('manaCost')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

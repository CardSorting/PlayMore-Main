<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Card Preview -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <livewire:card-creator.components.card-preview 
            :preview="$viewModel['preview']" />
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
                    <x-text-input 
                        wire:model.live="name" 
                        id="name" 
                        type="text" 
                        class="block w-full" 
                        placeholder="Enter card name" 
                        required />
                    @error('name') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>
            </div>

            <!-- Mana Cost -->
            <livewire:card-creator.components.mana-selector 
                :selector="$viewModel['manaSelector']" />

            <!-- Card Type -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-semibold text-gray-900">Card Type</h3>
                </div>
                
                <!-- Card Type Categories -->
                <div class="flex space-x-2">
                    @foreach($viewModel['typeSelector']['categories'] as $category)
                        <button type="button"
                            wire:click="$dispatch('typeCategoryChanged', '{{ $category }}')"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors"
                            :class="{ 
                                'bg-gray-800 text-white': '{{ $activeTypeCategory }}' === '{{ $category }}',
                                'bg-gray-100 text-gray-700 hover:bg-gray-200': '{{ $activeTypeCategory }}' !== '{{ $category }}'
                            }">
                            {{ $category }}
                        </button>
                    @endforeach
                </div>

                <livewire:card-creator.components.type-selector 
                    :selector="$viewModel['typeSelector']"
                    :active-category="$activeTypeCategory" />
            </div>

            <!-- Abilities -->
            <livewire:card-creator.components.ability-selector 
                :selector="$viewModel['abilitySelector']" />

            <!-- Flavor Text -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Flavor Text
                        <span class="text-sm font-normal text-gray-500">(optional)</span>
                    </h3>
                </div>
                <div>
                    <textarea 
                        wire:model.live="flavorText" 
                        id="flavorText"
                        rows="2" 
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        placeholder="Add flavor text to give your card more character"></textarea>
                    @error('flavorText') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>
            </div>

            <!-- Power/Toughness (for creatures) -->
            @if(str_starts_with($cardType, 'Creature'))
                <div class="space-y-4">
                    <div class="border-b border-gray-200 pb-2">
                        <h3 class="text-lg font-semibold text-gray-900">Power/Toughness</h3>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="grid grid-cols-5 gap-3">
                            @foreach(range(0, 4) as $power)
                                @foreach(range(0, 4) as $toughness)
                                    <button type="button" 
                                        wire:click="setPowerToughness('{{ $power }}/{{ $toughness }}')"
                                        class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $powerToughness === "$power/$toughness" ? 'bg-gray-800 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-100' }}">
                                        {{ $power }}/{{ $toughness }}
                                    </button>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                    @error('powerToughness') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-between pt-6 mt-6 border-t border-gray-200">
                <button type="button"
                    wire:click="resetState"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Start Over
                </button>

                <div class="flex space-x-3">
                    @if($hasUnsavedChanges)
                        <span class="inline-flex items-center px-3 py-2 text-sm text-yellow-800 bg-yellow-100 rounded-lg">
                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Unsaved Changes
                        </span>
                    @endif

                    <x-primary-button class="px-6 py-3 text-base !text-white !bg-gray-800 hover:!bg-gray-700">
                        {{ __('Create Card') }}
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>

    <!-- Notification Component -->
    <div x-data="{ notifications: [] }"
        @notify.window="notifications.push($event.detail); setTimeout(() => { notifications.shift() }, $event.detail.duration || 3000)"
        class="fixed bottom-0 right-0 z-50 p-4 space-y-4">
        <template x-for="notification in notifications" :key="notification.id">
            <div x-show="true"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="transform translate-x-full opacity-0"
                x-transition:enter-end="transform translate-x-0 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="transform translate-x-0 opacity-100"
                x-transition:leave-end="transform translate-x-full opacity-0"
                class="flex items-center p-4 space-x-3 text-white rounded-lg shadow-lg"
                :class="{
                    'bg-green-500': notification.type === 'success',
                    'bg-red-500': notification.type === 'error',
                    'bg-yellow-500': notification.type === 'warning',
                    'bg-blue-500': notification.type === 'info'
                }">
                <div class="flex-1" x-text="notification.message"></div>
                <button x-show="notification.action"
                    @click="$dispatch('notify-action', notification)"
                    class="px-3 py-1 text-sm font-medium bg-white bg-opacity-25 rounded-lg hover:bg-opacity-40"
                    x-text="notification.action">
                </button>
            </div>
        </template>
    </div>
</div>

@props(['activeTab'])

<div x-data="{ activeTab: '{{ $activeTab }}' }" class="mb-8">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Quantity categories">
            <button type="button"
                @click.prevent="activeTab = 'personal'"
                :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'personal',
                         'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'personal' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Personal Use
                <span class="ml-2 py-0.5 px-2.5 text-xs font-medium rounded-full" 
                    :class="{ 'bg-indigo-100 text-indigo-700': activeTab === 'personal',
                             'bg-gray-100 text-gray-600': activeTab !== 'personal' }">
                    1-5
                </span>
            </button>

            <button type="button"
                @click.prevent="activeTab = 'professional'"
                :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'professional',
                         'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'professional' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Professional
                <span class="ml-2 py-0.5 px-2.5 text-xs font-medium rounded-full" 
                    :class="{ 'bg-indigo-100 text-indigo-700': activeTab === 'professional',
                             'bg-gray-100 text-gray-600': activeTab !== 'professional' }">
                    20-50
                </span>
            </button>

            <button type="button"
                @click.prevent="activeTab = 'wholesale'"
                :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'wholesale',
                         'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'wholesale' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Wholesale
                <span class="ml-2 py-0.5 px-2.5 text-xs font-medium rounded-full" 
                    :class="{ 'bg-indigo-100 text-indigo-700': activeTab === 'wholesale',
                             'bg-gray-100 text-gray-600': activeTab !== 'wholesale' }">
                    100+
                </span>
            </button>
        </nav>
    </div>

    <div class="mt-6">
        <div x-cloak x-show="activeTab === 'personal'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-4xl mx-auto">
                {{ $personal }}
            </div>
        </div>

        <div x-cloak x-show="activeTab === 'professional'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-4xl mx-auto">
                {{ $professional }}
            </div>
        </div>

        <div x-cloak x-show="activeTab === 'wholesale'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-4xl mx-auto">
                {{ $wholesale }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
[x-cloak] { display: none !important; }
</style>
@endpush

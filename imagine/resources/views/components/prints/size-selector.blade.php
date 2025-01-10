@props(['sizes', 'selectedSize' => null])

<div x-data="sizeSelectorData('{{ $sizes->first()['category'] }}', '{{ $selectedSize }}')"
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Tab Navigation -->
    <x-prints.size-selector.tab-navigation 
        :categories="$sizes" />

    <!-- Tab Content -->
    <div class="mt-8">
        @foreach($sizes as $categoryData)
            <div x-cloak
                 x-show="activeTab === '{{ $categoryData['category'] }}'"
                 class="space-y-8">
                
                <!-- Size Guide Header -->
                <div class="max-w-4xl mx-auto">
                    <x-prints.size-selector.size-guide-header />
                </div>

                <!-- Size Grid -->
                <div>
                    <x-prints.size-selector.size-grid :sizes="$categoryData['sizes']" />
                </div>

                <!-- Perfect For List -->
                <div class="max-w-4xl mx-auto">
                    <x-prints.size-selector.perfect-for-list :category="$categoryData['category']" />
                </div>
            </div>
        @endforeach
    </div>

    <!-- Continue Button -->
    <div x-cloak
         x-show="selectedSize"
         class="fixed bottom-0 inset-x-0 bg-white border-t border-gray-200 p-4 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="ml-2 text-sm font-medium text-gray-900">Size selected</span>
                </div>
                <form method="POST" action="{{ route('prints.store-size', ['gallery' => request()->route('gallery')]) }}">
                    @csrf
                    <input type="hidden" name="size" x-model="selectedSize">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Continue to Material Selection
                        <svg class="ml-2 -mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

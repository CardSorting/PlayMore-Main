@props(['sizes'])

<div x-data="{ 
    showSizeGuide: false,
    categories: @js(collect($sizes)->groupBy('category')->toArray()),
    formatPrice(price) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(price / 100);
    }
}" class="space-y-8">
    <!-- Size Categories -->
    <div class="space-y-8">
        @foreach(collect($sizes)->groupBy('category') as $category => $categorySizes)
            <div>
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-base font-medium text-gray-900">{{ $category }}</h3>
                    <button type="button" 
                            @click="showSizeGuide = true"
                            class="text-sm text-blue-600 hover:text-blue-500">
                        Size guide
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach($categorySizes as $size => $details)
                        <label class="group relative"
                               :class="{ 'cursor-pointer': true }">
                            <input type="radio"
                                   name="size"
                                   value="{{ $size }}"
                                   x-model="$parent.selectedSize"
                                   @change="$dispatch('size-selected', $event.target.value)"
                                   class="peer sr-only"
                                   aria-labelledby="size-choice-{{ $size }}-label"
                                   aria-describedby="size-choice-{{ $size }}-description">
                            
                            <!-- Size Card -->
                            <div class="flex flex-col rounded-lg border bg-white p-4 shadow-sm ring-1 ring-gray-200 transition-all duration-200 hover:border-blue-500 hover:ring-blue-500 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500">
                                <!-- Size Info -->
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 id="size-choice-{{ $size }}-label" class="text-sm font-medium text-gray-900">
                                                {{ $details['name'] }}
                                            </h4>
                                            <p id="size-choice-{{ $size }}-description" class="mt-1 text-sm text-gray-500">
                                                {{ $details['dimensions'] }}
                                            </p>
                                            <p class="mt-1 text-sm font-medium text-gray-900">
                                                {{ '$' . number_format($details['price'] / 100, 2) }}
                                            </p>
                                        </div>
                                        <div class="ml-4">
                                            <div class="relative aspect-[3/4] w-16 overflow-hidden rounded border border-gray-200">
                                                <div class="absolute inset-0 flex items-center justify-center bg-gray-50">
                                                    <div class="h-12 w-8 border border-gray-300"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Use Case -->
                                    <p class="mt-2 text-sm text-gray-600">{{ $details['use_case'] }}</p>

                                    <!-- Popular Badge -->
                                    @if($details['popular'] ?? false)
                                        <span class="mt-2 inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                            Most Popular
                                        </span>
                                    @endif
                                </div>

                                <!-- Selected Indicator -->
                                <div class="absolute right-2 top-2 hidden text-blue-600 peer-checked:block">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.414-1.414L9 10.586 7.707 9.293a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Size Preview -->
    <div class="mt-6 rounded-lg border border-gray-200 bg-white">
        <div class="border-b border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-900">Size Preview</h3>
                <button type="button"
                        @click="showSizeGuide = true"
                        class="text-sm text-blue-600 hover:text-blue-500">
                    View size guide
                </button>
            </div>
            <p class="mt-1 text-sm text-gray-500">Compare print sizes with common objects</p>
        </div>
        
        <div class="p-4">
            <div class="relative h-48 rounded-lg bg-gray-50">
                <!-- Reference Objects -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <!-- Credit Card -->
                    <div class="absolute" style="left: 20%; transform: translateX(-50%)">
                        <div class="h-[2.125rem] w-[3.375rem] rounded border border-gray-300 bg-white"></div>
                        <p class="mt-2 text-center text-xs text-gray-500">Credit Card</p>
                    </div>
                    
                    <!-- Letter Paper -->
                    <div class="absolute" style="left: 50%; transform: translateX(-50%)">
                        <div class="h-[11rem] w-[8.5rem] rounded border border-gray-300 bg-white"></div>
                        <p class="mt-2 text-center text-xs text-gray-500">Letter Paper</p>
                    </div>
                    
                    <!-- Selected Size -->
                    <template x-if="$parent.selectedSize">
                        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                            <div class="border-2 border-blue-500"
                                 :style="{
                                     width: sizes[$parent.selectedSize].comparison_width + 'px',
                                     height: sizes[$parent.selectedSize].comparison_height + 'px'
                                 }">
                            </div>
                            <p class="mt-2 text-center text-xs font-medium text-blue-600" 
                               x-text="sizes[$parent.selectedSize].name"></p>
                        </div>
                    </template>
                </div>
            </div>
            <p class="mt-2 text-center text-xs text-gray-500">Not to scale. For visualization only.</p>
        </div>
    </div>

    <!-- Size Guide Modal -->
    <div x-show="showSizeGuide"
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showSizeGuide = false"></div>

            <div class="inline-block transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6 sm:align-middle">
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button type="button"
                            @click="showSizeGuide = false"
                            class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Print Size Guide</h3>
                        <div class="mt-4">
                            @foreach(collect($sizes)->groupBy('category') as $category => $categorySizes)
                                <div class="mb-6">
                                    <h4 class="text-base font-medium text-gray-900 mb-3">{{ $category }}</h4>
                                    <div class="space-y-4">
                                        @foreach($categorySizes as $size => $details)
                                            <div class="border-b border-gray-200 pb-4">
                                                <div class="flex items-center justify-between mb-1">
                                                    <div>
                                                        <h5 class="text-sm font-medium text-gray-900">{{ $details['name'] }}</h5>
                                                        <p class="text-sm text-gray-500">{{ $details['dimensions'] }}</p>
                                                    </div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ '$' . number_format($details['price'] / 100, 2) }}
                                                    </p>
                                                </div>
                                                <p class="text-sm text-gray-600">{{ $details['use_case'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Print Quality</h4>
                                <p class="text-sm text-gray-500">
                                    All prints are produced on premium archival paper with museum-quality pigment inks.
                                    Each print undergoes quality inspection and includes protective packaging.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

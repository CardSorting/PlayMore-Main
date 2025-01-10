@props(['sizes', 'selectedSize' => null])

<div x-data="{ 
    selected: '{{ $selectedSize }}',
    showSizeGuide: false,
    sizes: @js($sizes),
    getPrice(size) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(this.sizes[size].price);
    },
    getDimensions(size) {
        return this.sizes[size].dimensions;
    }
}" class="space-y-6">
    <!-- Size Selection Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($sizes as $size => $details)
            <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none"
                   :class="{
                       'border-blue-500 ring-2 ring-blue-500': selected === '{{ $size }}',
                       'border-gray-300': selected !== '{{ $size }}'
                   }">
                <input type="radio"
                       name="size"
                       value="{{ $size }}"
                       x-model="selected"
                       class="sr-only"
                       aria-labelledby="size-choice-{{ $size }}-label"
                       aria-describedby="size-choice-{{ $size }}-description">
                <div class="flex flex-1">
                    <div class="flex flex-col">
                        <span id="size-choice-{{ $size }}-label" class="block text-sm font-medium text-gray-900">
                            {{ $details['name'] }}
                        </span>
                        <span id="size-choice-{{ $size }}-description" class="mt-1 flex items-center text-sm text-gray-500">
                            {{ $details['dimensions'] }}
                        </span>
                        <span class="mt-2 text-sm font-medium text-gray-900">
                            ${{ number_format($details['price'], 2) }}
                        </span>
                        @if($details['popular'] ?? false)
                            <span class="mt-2 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                Most Popular
                            </span>
                        @endif
                    </div>
                </div>
                <svg class="h-5 w-5 text-blue-600" :class="{ 'invisible': selected !== '{{ $size }}' }"
                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                          clip-rule="evenodd" />
                </svg>
            </label>
        @endforeach
    </div>

    <!-- Size Comparison Tool -->
    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-gray-900">Size Comparison</h3>
            <button type="button"
                    @click="showSizeGuide = true"
                    class="text-sm text-blue-600 hover:text-blue-500">
                View Size Guide
            </button>
        </div>
        <div class="relative h-48 bg-white rounded border border-gray-200">
            <!-- Visual size comparison here -->
            <template x-for="(details, size) in sizes" :key="size">
                <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 border-2"
                     :class="{ 
                         'border-blue-500': selected === size,
                         'border-gray-200': selected !== size
                     }"
                     :style="`width: ${details.comparison_width}px; height: ${details.comparison_height}px`"
                     x-show="selected === size || size === 'medium'">
                </div>
            </template>
            <div class="absolute bottom-2 right-2 text-xs text-gray-500">
                Not to scale. For visualization only.
            </div>
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
                        <div class="mt-4 space-y-4">
                            @foreach($sizes as $size => $details)
                                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $details['name'] }}</h4>
                                        <p class="text-sm text-gray-500">{{ $details['dimensions'] }}</p>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">${{ number_format($details['price'], 2) }}</p>
                                </div>
                            @endforeach
                            <p class="mt-4 text-sm text-gray-500">
                                All prints are produced on premium archival paper with museum-quality pigment inks.
                                Prices include standard shipping.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

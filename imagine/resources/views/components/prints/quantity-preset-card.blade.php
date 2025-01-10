@props(['preset', 'order'])

<button type="button"
    class="relative cursor-pointer h-full w-full text-left" 
    x-on:click="selectedQuantity = {{ $preset->amount }}"
    :class="{
        'ring-2 ring-indigo-600': selectedQuantity === {{ $preset->amount }}
    }">
    <div class="relative p-5 rounded-xl border-2 transition-all duration-300 h-full flex flex-col"
        :class="{
            'border-indigo-600 ring-4 ring-indigo-600/20 bg-indigo-50/50': selectedQuantity === {{ $preset->amount }},
            'hover:border-indigo-300 hover:bg-indigo-50/30 hover:shadow-md': selectedQuantity !== {{ $preset->amount }}
        }">
        @if($preset->savings)
            <span class="absolute -top-3 -right-3 inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800 shadow-md ring-1 ring-green-100/50">
                {{ $preset->savings }}
            </span>
        @endif
        <div class="flex justify-between items-start mb-3">
            <div>
                <p class="font-medium text-gray-900 transition-colors"
                    :class="{ 'text-indigo-600': selectedQuantity === {{ $preset->amount }} }">
                    {{ $preset->label }}
                    @if($preset->popular)
                        <span class="ml-1 text-sm font-normal text-indigo-600">•</span>
                        <span class="ml-1 text-sm font-normal text-indigo-600">Popular choice</span>
                    @endif
                </p>
                <p class="text-sm text-gray-500 mt-1">{{ $preset->description }}</p>
                @if($preset->amount > 1)
                    <p class="text-xs text-indigo-600 mt-1.5">
                        @if($preset->amount === 3)
                            <span class="inline-flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                                </svg>
                                Horizontal or vertical arrangement
                            </span>
                        @elseif($preset->amount === 5)
                            <span class="inline-flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                </svg>
                                Asymmetrical layout
                            </span>
                        @elseif($preset->amount === 20)
                            <span class="inline-flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                </svg>
                                Multiple gallery walls
                            </span>
                        @elseif($preset->amount === 50)
                            <span class="inline-flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Boutique display options
                            </span>
                        @elseif($preset->amount === 100)
                            <span class="inline-flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Wholesale quantities
                            </span>
                        @elseif($preset->amount === 250)
                            <span class="inline-flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Bulk distribution
                            </span>
                        @endif
                    </p>
                @endif
            </div>
            <span class="flex items-center justify-center w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 font-bold text-xl transition-colors shadow-md"
                :class="{ 'bg-indigo-200': selectedQuantity === {{ $preset->amount }} }">
                {{ $preset->amount }}
            </span>
        </div>
        <div class="flex flex-col space-y-2 mt-auto pt-4 border-t border-gray-200">
            @if($preset->savings)
                <div class="flex justify-between items-baseline text-gray-500">
                    <p class="text-sm">Original price</p>
                    <p class="text-sm line-through">
                        ${{ number_format($preset->originalPrice / 100, 2) }}
                    </p>
                </div>
                <div class="flex justify-between items-baseline text-green-700">
                    <p class="text-sm font-medium">You save</p>
                    <p class="text-sm font-bold">
                        ${{ number_format($preset->savingsAmount / 100, 2) }}
                    </p>
                </div>
                <div class="flex justify-between items-baseline pt-2">
                    <p class="text-sm font-medium text-gray-900">Final price</p>
                    <p class="text-lg font-bold text-gray-900">
                        ${{ number_format($preset->discountedPrice / 100, 2) }}
                    </p>
                </div>
            @else
                <div class="flex justify-between items-baseline pt-2">
                    <p class="text-sm font-medium text-gray-900">Total price</p>
                    <p class="text-lg font-bold text-gray-900">
                        ${{ number_format($preset->originalPrice / 100, 2) }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</button>

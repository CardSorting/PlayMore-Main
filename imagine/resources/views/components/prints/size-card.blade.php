@props(['size', 'details'])
    
<label class="group relative cursor-pointer">
    <input type="radio"
           name="size"
           value="{{ $size }}"
           x-model="selectedSize"
           @change="$dispatch('size-selected', $event.target.value)"
           class="peer sr-only"
           aria-labelledby="size-choice-{{ $size }}-label"
           aria-describedby="size-choice-{{ $size }}-description">
    
    <div class="relative flex flex-col rounded-2xl border-2 bg-white p-5 shadow-sm transition-all duration-200 
              hover:border-indigo-500 hover:ring-1 hover:ring-indigo-500 hover:shadow-md
              peer-checked:border-indigo-600 peer-checked:ring-2 peer-checked:ring-indigo-600 peer-checked:shadow-md
              {{ $details['popular'] ?? false ? 'border-indigo-200 bg-indigo-50/50' : 'border-gray-200' }}">
        
        <!-- Popular Badge (Absolute Position) -->
        @if($details['popular'] ?? false)
            <span class="absolute -top-2 -right-2 inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-medium text-indigo-800 ring-1 ring-inset ring-indigo-500/20 shadow-sm">
                <svg class="mr-1 h-3.5 w-3.5 text-indigo-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                </svg>
                Popular Choice
            </span>
        @endif

        <!-- Size Name and Dimensions -->
        <div class="mb-3">
            <h4 id="size-choice-{{ $size }}-label" class="text-lg font-bold text-gray-900">
                {{ $details['name'] }}
            </h4>
            <div class="mt-1 flex items-center space-x-2">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                </svg>
                <p id="size-choice-{{ $size }}-description" class="text-sm font-medium text-gray-600">
                    {{ $details['dimensions'] }}
                </p>
            </div>
        </div>

        <!-- Visual Size Comparison -->
        <div class="relative h-32 mb-4 bg-gradient-to-br from-gray-50 to-white rounded-lg border border-gray-200 overflow-hidden">
            <!-- Reference Objects -->
            <div class="absolute left-4 bottom-3 flex items-end space-x-4">
                <!-- Credit Card Reference -->
                <div>
                    <div class="w-[53.98px] h-[34px] rounded bg-gray-400/20 border border-gray-400/40 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400/60" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                        </svg>
                    </div>
                    <div class="mt-1 text-[10px] text-center text-gray-400">
                        Credit Card
                    </div>
                </div>

                <!-- Smartphone Reference -->
                <div>
                    <div class="w-[34px] h-[68px] rounded-lg bg-gray-400/20 border border-gray-400/40 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-400/60" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                    </div>
                    <div class="mt-1 text-[10px] text-center text-gray-400">
                        Phone
                    </div>
                </div>
            </div>

            <!-- Print Size Preview -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="border-2 border-indigo-600/75 bg-indigo-50/25 transition-all duration-200 group-hover:border-indigo-500 peer-checked:border-indigo-600 shadow-sm"
                     x-bind:style="(() => {
                         const dims = getScaledDimensions({{ $details['comparison_width'] }}, {{ $details['comparison_height'] }});
                         return `width: ${dims.width}px; height: ${dims.height}px;`;
                     })()">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-400/50" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2zm0-2V6h12v12H6z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Price and Selection -->
        <div class="mt-auto space-y-3">
            <!-- Use Case Preview -->
            <p class="text-sm text-gray-600">
                {{ $details['description'] }}
            </p>

            <div class="flex items-center justify-between">
                <div class="flex items-baseline space-x-1">
                    <span class="text-lg font-bold text-gray-900">
                        {{ '$' . number_format($details['price'] / 100, 2) }}
                    </span>
                    <span class="text-sm text-gray-500">USD</span>
                </div>
                
                <!-- Selected Indicator -->
                <div class="hidden peer-checked:flex items-center text-indigo-600">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.414-1.414L9 10.586 7.707 9.293a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-1.5 text-sm font-medium">Selected</span>
                </div>
            </div>
        </div>
    </div>
</label>

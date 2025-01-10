@props(['sizes'])

<div class="max-w-5xl mx-auto px-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 justify-items-center">
        @foreach($sizes as $name => $details)
            <button type="button"
                    class="relative w-full max-w-xs text-left focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-lg"
                    x-data="{ 
                        isSelected() { return selectedSize === '{{ $name }}' }
                    }"
                    @click="selectedSize = '{{ $name }}'"
                    :class="{ 'ring-2 ring-indigo-500 ring-offset-2': isSelected() }">
                <div class="relative aspect-[4/3] rounded-lg border-2 transition-all duration-200"
                     :class="{
                         'border-indigo-500 shadow-lg': isSelected(),
                         'border-gray-200 hover:border-indigo-200 hover:shadow-md': !isSelected()
                     }">
                    <!-- Size Preview -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="relative"
                             x-data="{ 
                                 dimensions: utils.getScaledDimensions({{ $details['width'] }}, {{ $details['height'] }})
                             }">
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-white rounded"></div>
                            <div class="relative border border-gray-300 transition-all duration-200"
                                 :style="`width: ${dimensions.width}px; height: ${dimensions.height}px;`"
                                 :class="{ 'border-indigo-200': isSelected() }">
                                <!-- Size Label -->
                                <div class="absolute -top-6 left-1/2 -translate-x-1/2 px-1.5 py-0.5 bg-white/90 rounded text-[10px] font-medium text-gray-600 whitespace-nowrap shadow-sm">
                                    {{ $details['width'] }}" × {{ $details['height'] }}"
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selection Indicator -->
                    <div x-cloak
                         x-show="isSelected()"
                         class="absolute top-2 right-2 h-6 w-6 bg-indigo-500 rounded-full flex items-center justify-center shadow-sm">
                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <!-- Size Details -->
                <div class="mt-2 text-center">
                    <h3 class="text-sm font-medium transition-colors duration-200"
                        :class="{ 'text-indigo-600': isSelected(), 'text-gray-900': !isSelected() }">
                        {{ $name }}
                    </h3>
                </div>

                <!-- Comparison Object -->
                <div class="mt-1 text-center">
                    <span class="text-xs text-gray-400">
                        Size of {{ $details['comparison_object'] }}
                    </span>
                </div>
            </button>
        @endforeach
    </div>
</div>

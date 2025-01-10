@props(['image', 'alt', 'prompt'])

<div x-data="{
    selectedSize: $parent.selectedSize || '',
    selectedMaterial: $parent.selectedMaterial || 'premium_lustre',
    sizes: @js(config('prints.sizes')),
    materials: @js(config('prints.materials')),
    zoom: false,
    get frameStyle() {
        if (!this.selectedMaterial) return {};
        const material = this.materials[this.selectedMaterial];
        return {
            'border': material.name === 'Canvas' ? 'none' : '20px solid #f3f4f6',
            'padding': material.name === 'Canvas' ? '40px' : '8px',
            'box-shadow': material.name === 'Canvas' 
                ? '8px 8px 16px rgba(0,0,0,0.1)' 
                : '0 4px 6px -1px rgba(0,0,0,0.1)',
            'background': material.name === 'Metallic' 
                ? 'linear-gradient(45deg, #f3f4f6 0%, #fff 50%, #f3f4f6 100%)' 
                : '#fff'
        };
    }
}" class="space-y-4">
    <!-- Main Image with Frame -->
    <div class="relative aspect-[4/3] overflow-hidden rounded-lg bg-gray-100">
        <div class="absolute inset-0 flex items-center justify-center"
             :class="{ 'cursor-zoom-in': !zoom, 'cursor-zoom-out': zoom }"
             @click="zoom = !zoom">
            <div class="relative h-full w-full transition-transform duration-500"
                 :class="{ 'scale-150': zoom }"
                 :style="frameStyle">
                <img src="{{ $image }}" 
                     alt="{{ $alt }}" 
                     class="h-full w-full object-contain"
                     :class="{ 'hover:scale-110 transition-transform duration-300': !zoom }">
            </div>
        </div>

        <!-- Prompt Overlay -->
        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
            <p class="text-sm text-white">{{ $prompt }}</p>
        </div>

        <!-- Material Preview Badge -->
        <div class="absolute right-4 top-4">
            <div class="rounded-full bg-white px-3 py-1 text-sm font-medium text-gray-900 shadow-sm ring-1 ring-gray-900/10">
                <span x-text="materials[selectedMaterial].name"></span>
            </div>
        </div>
    </div>

    <!-- Material Preview Thumbnails -->
    <div class="grid grid-cols-3 gap-4">
        @foreach(config('prints.materials') as $id => $material)
            <button type="button"
                    @click="selectedMaterial = '{{ $id }}'"
                    class="relative aspect-[4/3] overflow-hidden rounded-lg focus:outline-none"
                    :class="{
                        'ring-2 ring-offset-2 ring-blue-500': selectedMaterial === '{{ $id }}',
                        'ring-1 ring-gray-200': selectedMaterial !== '{{ $id }}'
                    }">
                <div class="absolute inset-0 flex items-center justify-center bg-gray-100">
                    <div class="h-full w-full p-2">
                        <img src="{{ $image }}" 
                             alt="{{ $material['name'] }}" 
                             class="h-full w-full object-contain"
                             style="{{ $id === 'metallic' ? 'filter: contrast(1.1) brightness(1.05);' : '' }}">
                    </div>
                </div>
                <div class="absolute inset-x-0 bottom-0 bg-white bg-opacity-75 p-2">
                    <p class="text-center text-xs font-medium">{{ $material['name'] }}</p>
                </div>
            </button>
        @endforeach
    </div>

    <!-- Size Comparison -->
    <div class="rounded-lg border border-gray-200 bg-white p-4">
        <h3 class="text-sm font-medium text-gray-900">Size Comparison</h3>
        <div class="relative mt-4 h-48">
            <div class="absolute inset-0 flex items-center justify-center">
                <!-- Reference Object -->
                <div class="relative">
                    <svg class="h-12 w-12 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <p class="mt-2 text-center text-xs text-gray-500">12 inches</p>
                </div>

                <!-- Print Size Preview -->
                <template x-if="selectedSize">
                    <div class="absolute border-2 border-blue-500"
                         :style="{
                             width: sizes[selectedSize].comparison_width + 'px',
                             height: sizes[selectedSize].comparison_height + 'px'
                         }">
                        <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 whitespace-nowrap text-xs text-gray-500">
                            <span x-text="sizes[selectedSize].name"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

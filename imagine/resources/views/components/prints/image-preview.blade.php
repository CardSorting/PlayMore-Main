@props(['image', 'alt', 'prompt'])

<div x-data="{ 
    isZoomed: false,
    imageUrl: '{{ $image }}',
    startX: 0,
    startY: 0,
    panning: false,
    currentX: 0,
    currentY: 0,
    scale: 1
}" class="relative">
    <!-- Main Image -->
    <div class="aspect-w-1 aspect-h-1 mb-4">
        <img :src="imageUrl" 
             :alt="'{{ $alt }}'" 
             class="w-full h-full object-cover rounded-lg shadow-lg cursor-zoom-in"
             :class="{ 'cursor-zoom-in': !isZoomed, 'cursor-move': isZoomed }"
             @click="isZoomed = !isZoomed"
             :style="isZoomed ? `transform: scale(${scale}) translate(${currentX}px, ${currentY}px)` : ''"
             @mousedown="if(isZoomed) { panning = true; startX = $event.clientX - currentX; startY = $event.clientY - currentY; }"
             @mousemove="if(panning && isZoomed) { 
                currentX = $event.clientX - startX;
                currentY = $event.clientY - startY;
             }"
             @mouseup="panning = false"
             @mouseleave="panning = false">

        <!-- Zoom Controls -->
        <div class="absolute top-4 right-4 flex space-x-2" @click.stop>
            <button x-show="isZoomed"
                    @click="scale = Math.min(scale + 0.5, 3)"
                    class="p-2 bg-white bg-opacity-75 rounded-full shadow-sm hover:bg-opacity-100 focus:outline-none">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </button>
            <button x-show="isZoomed"
                    @click="scale = Math.max(scale - 0.5, 1)"
                    class="p-2 bg-white bg-opacity-75 rounded-full shadow-sm hover:bg-opacity-100 focus:outline-none">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                </svg>
            </button>
            <button x-show="isZoomed"
                    @click="isZoomed = false; scale = 1; currentX = 0; currentY = 0;"
                    class="p-2 bg-white bg-opacity-75 rounded-full shadow-sm hover:bg-opacity-100 focus:outline-none">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Image Details -->
    <div class="mt-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Your Custom Print</h2>
        <p class="text-gray-600">{{ $prompt }}</p>
    </div>

    <!-- Zoom Instructions -->
    <div x-show="!isZoomed" class="mt-2 text-sm text-gray-500 flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        Click to zoom and examine details
    </div>

    <!-- Zoom Modal Overlay -->
    <div x-show="isZoomed" 
         class="fixed inset-0 bg-black bg-opacity-75 z-50"
         @click="isZoomed = false; scale = 1; currentX = 0; currentY = 0;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>
</div>

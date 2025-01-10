@props(['image', 'alt', 'prompt'])

<div x-data="{ zoom: false }" class="space-y-6">
    <!-- Main Image Container -->
    <div class="aspect-[4/3] w-full overflow-hidden rounded-lg bg-gray-100">
        <div class="relative h-full w-full">
            <!-- Main Image -->
            <div class="group relative h-full"
                 :class="{ 'cursor-zoom-in': !zoom, 'cursor-zoom-out': zoom }"
                 @click="zoom = !zoom">
                <div class="relative h-full w-full transition-all duration-500"
                     :class="{ 'scale-150': zoom }">
                    <img src="{{ $image }}" 
                         alt="{{ $alt }}" 
                         class="h-full w-full object-contain transition-opacity"
                         :class="{ 'group-hover:opacity-95': !zoom }">
                </div>

                <!-- Zoom Icon -->
                <div class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-white/80 opacity-0 shadow-sm transition-opacity group-hover:opacity-100"
                     x-show="!zoom">
                    <svg class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                    </svg>
                </div>
            </div>


            <!-- Prompt -->
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                <p class="text-sm font-medium text-white">{{ $prompt }}</p>
            </div>
        </div>
    </div>


</div>

@props(['image', 'alt', 'prompt'])

<div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg bg-gray-100">
    <img 
        src="{{ $image }}" 
        alt="{{ $alt }}"
        class="h-full w-full object-cover object-center"
        loading="lazy"
    />
    
    <!-- Prompt Overlay -->
    <div class="absolute inset-0 flex items-end">
        <div class="w-full bg-gradient-to-t from-black/60 to-transparent p-4">
            <p class="text-sm text-white line-clamp-2">
                {{ $prompt }}
            </p>
        </div>
    </div>

    <!-- AI Badge -->
    <div class="absolute top-4 left-4">
        <span class="inline-flex items-center px-2 py-1 rounded bg-black/50 backdrop-blur-sm">
            <svg class="w-4 h-4 text-white mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 1c3.866 0 7 3.134 7 7 0 1.756-.647 3.36-1.714 4.59l3.42 3.42a1 1 0 01-1.414 1.414l-3.42-3.42A6.96 6.96 0 0110 15c-3.866 0-7-3.134-7-7s3.134-7 7-7zm0 2a5 5 0 100 10 5 5 0 000-10z" clip-rule="evenodd"/>
                <path d="M10 12a4 4 0 100-8 4 4 0 000 8z"/>
            </svg>
            <span class="text-xs font-medium text-white">AI Generated</span>
        </span>
    </div>

    <!-- Zoom Icon -->
    <div class="absolute top-4 right-4">
        <button 
            type="button"
            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-black/50 backdrop-blur-sm text-white hover:bg-black/60 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            x-data=""
            @click="$dispatch('open-modal', 'zoom-image')"
        >
            <span class="sr-only">Zoom image</span>
            <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 00-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 00.75-.75v-4a.75.75 0 011.5 0v4A2.25 2.25 0 0112.75 17h-8.5A2.25 2.25 0 012 14.75v-8.5A2.25 2.25 0 014.25 4h5a.75.75 0 010 1.5h-5z" clip-rule="evenodd" />
                <path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 001.06.053L16.5 4.44v2.81a.75.75 0 001.5 0v-4.5a.75.75 0 00-.75-.75h-4.5a.75.75 0 000 1.5h2.553l-9.056 8.194a.75.75 0 00-.053 1.06z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>

<!-- Zoom Modal -->
<x-modal name="zoom-image" maxWidth="7xl">
    <div class="relative">
        <img 
            src="{{ $image }}" 
            alt="{{ $alt }}"
            class="w-full h-full object-contain"
        />
        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
            <p class="text-sm text-white">
                {{ $prompt }}
            </p>
        </div>
    </div>
</x-modal>

@props(['image', 'alt', 'prompt'])

<div class="sticky top-0 lg:h-[600px] overflow-hidden rounded-lg bg-gray-100">
    <div class="relative h-full">
        <img src="{{ $image }}" alt="{{ $alt }}" class="h-full w-full object-cover">
        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
            <p class="text-sm text-white">{{ $prompt }}</p>
        </div>
    </div>
</div>

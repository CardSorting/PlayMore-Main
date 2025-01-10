@props(['gallery'])

<div class="group bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
    <!-- Image Container -->
    <div class="relative">
        <!-- Badges Container -->
        <div class="absolute top-2 left-2 right-2 flex justify-between z-10">
            @if($gallery->getPopularityScore() > 0.7)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Popular
                </span>
            @endif
            @if(!$gallery->is_available)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-800 text-white">
                    Sold Out
                </span>
            @endif
        </div>

        <!-- Image -->
        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-t-lg">
            <img src="{{ $gallery->image_url }}" 
                 alt="{{ $gallery->prompt }}" 
                 class="h-full w-full object-cover object-center transform transition-transform duration-300 group-hover:scale-105">
        </div>
    </div>

    <!-- Content Container -->
    <div class="p-4">
        <!-- Title -->
        <h3 class="text-sm font-medium text-gray-900 line-clamp-1 mb-2">
            <a href="{{ route('public.gallery.show', ['user' => $gallery->user->name, 'gallery' => $gallery]) }}">
                {{ $gallery->type === 'card' ? $gallery->name : $gallery->prompt }}
            </a>
        </h3>

        <!-- Stats -->
        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-3">
            <span>{{ $gallery->created_at->diffForHumans() }}</span>
            <span>•</span>
            <span>{{ $gallery->views_count }} views</span>
            @if($gallery->printOrders()->count() > 0)
                <span>•</span>
                <span>{{ $gallery->printOrders()->count() }} sold</span>
            @endif
        </div>

        <!-- Card Details -->
        @if($gallery->type === 'card')
            <div class="space-y-1 mb-3">
                <div class="flex items-center text-sm">
                    <span class="font-medium text-gray-900">{{ $gallery->mana_cost }}</span>
                    <span class="mx-2 text-gray-500">•</span>
                    <span class="text-gray-700">{{ $gallery->card_type }}</span>
                </div>
                @if($gallery->power_toughness)
                    <p class="text-sm text-gray-600">{{ $gallery->power_toughness }}</p>
                @endif
                <p class="text-sm text-gray-500 line-clamp-2">{{ $gallery->abilities }}</p>
            </div>
        @endif

        <!-- Price and Action -->
        @if($gallery->type === 'image')
            <div class="mt-4 flex flex-col space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-lg font-semibold text-gray-900">From ${{ number_format($gallery->price / 100, 2) }}</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        2-5 business days
                    </div>
                </div>
                <a href="{{ route('prints.select-size', ['gallery' => $gallery]) }}" 
                   class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Order Print
                </a>
            </div>
        @endif
    </div>
</div>

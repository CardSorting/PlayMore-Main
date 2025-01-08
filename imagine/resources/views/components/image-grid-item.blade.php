<div class="bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="image-container relative h-64 w-full cursor-pointer rounded-lg overflow-hidden" 
         onclick="showDetails('{{ $image->prompt }}', '{{ $image->image_url }}', '{{ $image->aspect_ratio }}', '{{ $image->process_mode }}', '{{ $image->task_id }}')"
         role="button"
         tabindex="0"
         aria-label="View details for image: {{ $image->prompt }}">
        <img src="{{ $image->image_url }}" 
             alt="{{ $image->prompt }}"
             class="w-full h-full object-cover rounded-lg">
        
        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-200 rounded-lg">
            <div class="absolute inset-0 flex flex-col items-center justify-center p-6">
                <p class="text-white text-center mb-6 line-clamp-3 text-shadow-lg font-medium">{{ $image->prompt }}</p>
                <div class="flex space-x-3">
                    <a href="{{ $image->image_url }}" 
                       download 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                       onclick="event.stopPropagation()"
                       aria-label="Download image">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download
                    </a>
                    <button class="inline-flex items-center px-4 py-2 bg-gray-800 text-white font-medium rounded-md hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                            aria-label="View image details">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Details
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="p-2 bg-gray-50 border-t">
        @php
            $hasCard = \App\Models\Gallery::where('type', 'card')
                ->where('image_url', $image->image_url)
                ->exists();
        @endphp
        @if($hasCard)
            <div class="flex items-center justify-center px-3 py-1.5 bg-gray-200 text-gray-500 text-sm font-medium rounded cursor-not-allowed"
                 aria-label="Card already created for this image">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Card Created</span>
            </div>
        @else
            <a href="{{ route('cards.create', ['image_id' => $image->id]) }}"
               class="flex items-center justify-center px-3 py-1.5 bg-purple-500 text-black text-sm font-medium rounded hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
               aria-label="Create card from this image">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Create Card</span>
            </a>
        @endif
    </div>
</div>

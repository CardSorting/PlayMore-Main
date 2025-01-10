<div class="bg-white overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-200 rounded-xl ring-1 ring-black/5">
    <div class="image-container relative h-64 w-full cursor-pointer rounded-lg overflow-hidden" 
         onclick="ImageDetailsModal.show('{{ addslashes($image->prompt) }}', '{{ $image->image_url }}', '{{ $image->aspect_ratio }}', '{{ $image->process_mode }}', '{{ $image->task_id }}', '{{ $image->created_at_for_humans }}', {{ json_encode($image->metadata) }})"
         role="button"
         tabindex="0"
         aria-label="View details for image: {{ $image->prompt }}">
        <img src="{{ $image->image_url }}" 
             alt="{{ $image->prompt }}"
             class="w-full h-full object-cover rounded-lg">
        
        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-200 rounded-lg">
            <div class="absolute inset-0 flex items-center justify-center p-6">
                <p class="text-white text-center line-clamp-3 text-shadow-lg font-medium">{{ $image->prompt }}</p>
            </div>
        </div>
    </div>
    <div class="p-3 bg-gradient-to-b from-gray-50 to-white border-t space-y-2">
        <div class="flex justify-between gap-2">
            @php
                $hasCard = \App\Models\Gallery::where('type', 'card')
                    ->where('image_url', $image->image_url)
                    ->exists() || 
                \App\Models\GlobalCard::where('image_url', $image->image_url)
                    ->exists();
                $currentTab = request('tab', 'create');
            @endphp
            
            @if($currentTab === 'create')
                @if($hasCard)
                    <div class="w-full flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed transition-all duration-200 ease-in-out"
                         aria-label="Card already created for this image">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Card Created</span>
                    </div>
                @else
                    <a href="{{ route('cards.create', ['image_id' => $image->id]) }}"
                       class="w-full flex items-center justify-center px-4 py-2 bg-purple-500 text-black text-sm font-medium rounded-lg hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 ease-in-out shadow-sm hover:shadow"
                       aria-label="Create card from this image">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Create Card</span>
                    </a>
                @endif
            @else
                <a href="{{ route('prints.create', ['gallery' => $image->id]) }}"
                   class="w-full flex items-center justify-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 ease-in-out shadow-sm hover:shadow"
                   aria-label="Order print of this image">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Order Print</span>
                </a>
            @endif
        </div>
    </div>
</div>

<div class="border dark:border-gray-700 rounded-lg p-4 hover:shadow-lg transition-shadow">
    <div class="flex justify-between items-start mb-2">
        <h3 class="text-lg font-medium">{{ $pack->name }}</h3>
        <div class="flex flex-col items-end">
            <span class="px-2 py-1 text-sm bg-purple-100 text-purple-800 rounded mb-1">
                {{ number_format($pack->price) }} PULSE
            </span>
            @if($showListingDate)
                <span class="text-xs text-gray-500">
                    Listed {{ $pack->listed_at->diffForHumans() }}
                </span>
            @endif
        </div>
    </div>
    
    @if($pack->description)
        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ $pack->description }}</p>
    @endif

    <div class="mb-4">
        <div class="pack-card relative aspect-[7/5] rounded-lg overflow-hidden group cursor-pointer">
            <!-- Preview card -->
            <div class="absolute inset-0">
                <img src="{{ $pack->cards->first()->image_url }}" 
                     alt="Pack preview" 
                     class="w-full h-full object-cover opacity-50 mix-blend-luminosity">
            </div>
            <!-- Base gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-purple-600/80 via-purple-700/85 to-purple-900/95 mix-blend-multiply"></div>
            <!-- Foil pattern -->
            <div class="absolute inset-0 foil-pattern opacity-30"></div>
            <!-- Glow effect -->
            <div class="absolute inset-0 bg-gradient-to-t from-purple-500/20 to-transparent"></div>
            <!-- Shimmer effect -->
            <div class="shimmer"></div>
            <!-- Pack design elements -->
            <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                <!-- Outer border -->
                <div class="absolute inset-2 border-2 border-gold opacity-30 rounded-lg"></div>
                <!-- Inner border -->
                <div class="absolute inset-4 border border-gold opacity-20 rounded"></div>
                <!-- Pack content -->
                <div class="relative text-center z-10 transform transition-transform duration-500 group-hover:scale-105">
                    <div class="text-gold font-bold text-2xl mb-3 font-beleren tracking-wider">
                        SEALED PACK
                    </div>
                    <div class="text-gold/90 font-medium text-lg mb-4 font-beleren">
                        {{ $pack->cards_count }} CARDS
                    </div>
                    <div class="text-gold/70 text-xs font-medium uppercase tracking-widest">
                        Premium Collection
                    </div>
                </div>
                <!-- Corner decorations -->
                <div class="absolute top-6 left-6 w-3 h-3 border-t-2 border-l-2 border-gold opacity-50"></div>
                <div class="absolute top-6 right-6 w-3 h-3 border-t-2 border-r-2 border-gold opacity-50"></div>
                <div class="absolute bottom-6 left-6 w-3 h-3 border-b-2 border-l-2 border-gold opacity-50"></div>
                <div class="absolute bottom-6 right-6 w-3 h-3 border-b-2 border-r-2 border-gold opacity-50"></div>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center">
        <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ $pack->cards_count }} cards
        </span>
        <form action="{{ route('marketplace.seller.unlist', $pack) }}" 
              method="POST" 
              x-data="{ submitting: false }"
              @submit.prevent="
                if (confirm('Are you sure you want to remove this pack from the marketplace?')) {
                    submitting = true;
                    $el.submit();
                }
              "
              class="inline">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="submitting">
                <span x-show="!submitting">Remove Listing</span>
                <span x-show="submitting" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Removing...
                </span>
            </button>
        </form>
    </div>
</div>

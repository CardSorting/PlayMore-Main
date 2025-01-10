<div class="relative">
    <!-- Card Frame -->
    <div class="relative aspect-[63/88] w-full max-w-md mx-auto">
        <div class="absolute inset-0 rounded-[4.7%] overflow-hidden shadow-xl">
            <!-- Card Background -->
            <div class="absolute inset-0 bg-gradient-to-b from-gray-200 to-gray-300"></div>

            <!-- Card Frame Elements -->
            <x-card-frame-elements :color-identity="$preview['colorIdentity']" />

            <!-- Title Bar -->
            <x-card-title-bar>
                <div class="flex justify-between items-center px-3 py-1">
                    <h3 class="text-black font-semibold truncate">{{ $preview['name'] }}</h3>
                    <div class="flex items-center space-x-1">
                        @foreach($preview['manaCost'] as $symbol)
                            <x-mana-symbol :symbol="$symbol" class="w-4 h-4" />
                        @endforeach
                    </div>
                </div>
            </x-card-title-bar>

            <!-- Art Box -->
            <x-card-art-box>
                <img src="{{ $preview['imageUrl'] }}" 
                    alt="Card Art" 
                    class="absolute inset-0 w-full h-full object-cover"
                    loading="lazy">
                <div class="absolute bottom-0 right-0 p-1 text-xs text-white bg-black bg-opacity-50">
                    Art by {{ $preview['imageAuthor'] }}
                </div>
            </x-card-art-box>

            <!-- Type Line -->
            <x-card-type-line :color-identity="$preview['colorIdentity']">
                <div class="px-3 py-1">
                    <p class="text-black font-semibold">{{ $preview['cardType'] }}</p>
                </div>
            </x-card-type-line>

            <!-- Text Box -->
            <x-card-text-box>
                <div class="px-3 py-2 space-y-2">
                    <!-- Card Abilities -->
                    @if($preview['abilities'])
                        <div class="text-sm">
                            {!! nl2br(e($preview['abilities'])) !!}
                        </div>
                    @endif

                    <!-- Flavor Text -->
                    @if($preview['flavorText'])
                        <div class="text-sm italic text-gray-600 pt-2 border-t border-gray-200">
                            {{ $preview['flavorText'] }}
                        </div>
                    @endif
                </div>
            </x-card-text-box>

            <!-- Info Line -->
            <x-card-info-line :color-identity="$preview['colorIdentity']">
                <div class="flex justify-between items-center px-3 py-1">
                    <div class="text-xs text-gray-600">
                        #{{ str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}/{{ str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}
                    </div>
                    @if($preview['powerToughness'])
                        <div class="text-black font-semibold">
                            {{ $preview['powerToughness'] }}
                        </div>
                    @endif
                </div>
            </x-card-info-line>
        </div>
    </div>

    <!-- Card Stats -->
    <div class="mt-6 grid grid-cols-2 gap-4 text-center">
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="text-sm font-medium text-gray-500">Color Identity</div>
            <div class="mt-2 flex justify-center space-x-2">
                @forelse($preview['colorIdentity'] as $color)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                        {{ match($color) {
                            'White' => 'bg-yellow-100 text-yellow-800',
                            'Blue' => 'bg-blue-100 text-blue-800',
                            'Black' => 'bg-gray-100 text-gray-800',
                            'Red' => 'bg-red-100 text-red-800',
                            'Green' => 'bg-green-100 text-green-800',
                            default => 'bg-gray-100 text-gray-800'
                        } }}">
                        {{ $color }}
                    </span>
                @empty
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Colorless
                    </span>
                @endforelse
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <div class="text-sm font-medium text-gray-500">Mana Value</div>
            <div class="mt-2 text-2xl font-bold text-gray-900">
                {{ $preview['convertedManaCost'] }}
            </div>
        </div>
    </div>
</div>

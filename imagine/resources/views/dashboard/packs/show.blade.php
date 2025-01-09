<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-xl font-semibold">{{ $pack->name }}</h2>
                            @if($pack->description)
                                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $pack->description }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 text-sm {{ $pack->is_sealed ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} rounded-full">
                                {{ $pack->is_sealed ? 'Sealed' : 'Open' }}
                            </span>
                            @if(!$pack->is_sealed && $pack->cards->count() === $pack->card_limit)
                                <form method="POST" action="{{ route('packs.seal', $pack) }}">
                                    @csrf
                                    <x-primary-button>Seal Pack</x-primary-button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Pack Progress</span>
                                <span class="text-sm">{{ $pack->cards->count() }} / {{ $pack->card_limit }} cards</span>
                            </div>
                            <div class="mt-2 h-2 bg-gray-200 dark:bg-gray-600 rounded-full">
                                <div class="h-2 bg-blue-500 rounded-full" style="width: {{ ($pack->cards->count() / $pack->card_limit) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>

                    @if(!$pack->is_sealed && $pack->cards->count() < $pack->card_limit)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium mb-4">Add Cards to Pack</h3>
                            @if($availableCards->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400">You don't have any cards available to add to this pack.</p>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($availableCards as $card)
                                        <div class="border dark:border-gray-700 rounded-lg p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <h4 class="font-medium">{{ $card->name }}</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $card->card_type }}</p>
                                                </div>
                                                <form method="POST" action="{{ route('packs.add-card', $pack) }}">
                                                    @csrf
                                                    <input type="hidden" name="card_id" value="{{ $card->id }}">
                                                    <button type="submit" class="text-blue-500 hover:text-blue-700">
                                                        Add to Pack
                                                    </button>
                                                </form>
                                            </div>
                                            @if($card->image_url)
                                                <img src="{{ $card->image_url }}" alt="{{ $card->name }}" class="w-full h-40 object-cover rounded mt-2">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    <div>
                        <h3 class="text-lg font-medium mb-4">Pack Contents</h3>
                        @if($pack->cards->isEmpty())
                            <p class="text-gray-500 dark:text-gray-400">This pack is currently empty.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($pack->cards as $card)
                                    <div class="border dark:border-gray-700 rounded-lg p-4">
                                        <div class="mb-2">
                                            <h4 class="font-medium">{{ $card->name }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $card->card_type }}</p>
                                        </div>
                                        @if($card->image_url)
                                            <img src="{{ $card->image_url }}" alt="{{ $card->name }}" class="w-full h-40 object-cover rounded">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

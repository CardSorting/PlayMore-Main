<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">Your Card Packs</h2>
                        <a href="{{ route('packs.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create New Pack
                        </a>
                    </div>

                    @if($packs->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">You haven't created any card packs yet.</p>
                            <p class="text-gray-500 dark:text-gray-400 mt-2">Create a pack to start collecting cards!</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($packs as $pack)
                                <div class="border dark:border-gray-700 rounded-lg p-4 hover:shadow-lg transition-shadow">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-lg font-medium">{{ $pack->name }}</h3>
                                        <span class="px-2 py-1 text-xs {{ $pack->is_sealed ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} rounded">
                                            {{ $pack->is_sealed ? 'Sealed' : 'Open' }}
                                        </span>
                                    </div>
                                    
                                    @if($pack->description)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ $pack->description }}</p>
                                    @endif

                                    <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ $pack->cards_count }} / {{ $pack->card_limit }} cards</span>
                                        <a href="{{ route('packs.show', $pack) }}" class="text-blue-500 hover:text-blue-700">
                                            View Details →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

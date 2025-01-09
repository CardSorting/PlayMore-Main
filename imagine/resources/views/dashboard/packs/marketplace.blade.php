<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">Pack Marketplace</h2>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500">Your Balance: {{ number_format(Auth::user()->getCreditBalance()) }} PULSE</span>
                            <a href="{{ route('packs.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Your Packs
                            </a>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <a href="{{ route('packs.marketplace', ['tab' => 'browse']) }}" 
                               class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $tab === 'browse' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Browse Marketplace
                            </a>
                            <a href="{{ route('packs.marketplace', ['tab' => 'selling']) }}" 
                               class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $tab === 'selling' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Selling Dashboard
                            </a>
                            <a href="{{ route('packs.marketplace', ['tab' => 'purchases']) }}" 
                               class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $tab === 'purchases' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Purchase History
                            </a>
                        </nav>
                    </div>

                    <!-- Browse Tab -->
                    @if($tab === 'browse')
                        @if($availablePacks->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">No packs are currently available in the marketplace.</p>
                                <p class="text-gray-500 dark:text-gray-400 mt-2">Check back later for new listings!</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($availablePacks as $pack)
                                    <div class="border dark:border-gray-700 rounded-lg p-4 hover:shadow-lg transition-shadow">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="text-lg font-medium">{{ $pack->name }}</h3>
                                            <div class="flex flex-col items-end">
                                                <span class="px-2 py-1 text-sm bg-purple-100 text-purple-800 rounded mb-1">
                                                    {{ number_format($pack->price) }} PULSE
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    by {{ $pack->user->name }}
                                                </span>
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
                                            <form action="{{ route('packs.purchase', $pack) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm"
                                                        onclick="return confirm('Are you sure you want to purchase this pack for {{ number_format($pack->price) }} PULSE?')">
                                                    Purchase Pack
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif

                    <!-- Selling Tab -->
                    @if($tab === 'selling')
                        <div class="space-y-8">
                            <!-- Active Listings -->
                            <div>
                                <h3 class="text-lg font-medium mb-4">Active Listings</h3>
                                @if($listedPacks->isEmpty())
                                    <p class="text-gray-500 dark:text-gray-400">You don't have any packs listed for sale.</p>
                                @else
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach($listedPacks as $pack)
                                            <div class="border dark:border-gray-700 rounded-lg p-4">
                                                <div class="flex justify-between items-start mb-2">
                                                    <h4 class="font-medium">{{ $pack->name }}</h4>
                                                    <span class="px-2 py-1 text-sm bg-purple-100 text-purple-800 rounded">
                                                        {{ number_format($pack->price) }} PULSE
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-500 mb-4">Listed {{ $pack->listed_at->diffForHumans() }}</p>
                                                <form action="{{ route('packs.unlist', $pack) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                        Remove Listing
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Sales History -->
                            <div>
                                <h3 class="text-lg font-medium mb-4">Sales History</h3>
                                @if($soldPacks->isEmpty())
                                    <p class="text-gray-500 dark:text-gray-400">You haven't sold any packs yet.</p>
                                @else
                                    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
                                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($soldPacks as $pack)
                                                <li class="px-4 py-4 sm:px-6">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <div class="ml-3">
                                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                    {{ $pack->name }}
                                                                </p>
                                                                <p class="text-sm text-gray-500">
                                                                    Sold to {{ $pack->user->name }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="ml-2 flex-shrink-0 flex">
                                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                {{ number_format($pack->price) }} PULSE
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Purchases Tab -->
                    @if($tab === 'purchases')
                        @if($purchasedPacks->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">You haven't purchased any packs yet.</p>
                                <p class="text-gray-500 dark:text-gray-400 mt-2">Browse the marketplace to find packs!</p>
                            </div>
                        @else
                            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
                                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($purchasedPacks as $pack)
                                        <li class="px-4 py-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $pack->name }}
                                                        </p>
                                                        <p class="text-sm text-gray-500">
                                                            Purchased from {{ $pack->user->name }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="ml-2 flex-shrink-0 flex space-x-4">
                                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                        {{ number_format($pack->price) }} PULSE
                                                    </p>
                                                    <a href="{{ route('packs.show', $pack) }}" 
                                                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200">
                                                        View Pack
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- List Pack Modal -->
    <div x-data="{ open: false, selectedPack: null }" @keydown.escape.window="open = false">
        <!-- Modal Trigger Button -->
        <div class="fixed bottom-8 right-8">
            <button @click="open = true" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>List a Pack</span>
            </button>
        </div>

        <!-- Modal -->
        <div x-show="open" 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             x-transition>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4"
                 @click.away="open = false">
                <h3 class="text-lg font-semibold mb-4">List Pack on Marketplace</h3>
                
                <form x-data="{ selectedPack: '' }" 
                      x-on:submit.prevent="
                        const form = $el;
                        form.action = '{{ url('/dashboard/packs') }}/' + selectedPack + '/list';
                        form.submit();
                      " 
                      method="POST" 
                      id="listPackForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Select Pack
                        </label>
                        <select x-model="selectedPack"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                required>
                            <option value="">Select a sealed pack...</option>
                            @foreach(Auth::user()->packs()->where('is_sealed', true)->where('is_listed', false)->get() as $pack)
                                <option value="{{ $pack->id }}">{{ $pack->name }} ({{ $pack->cards()->count() }} cards)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Price (PULSE)
                        </label>
                        <input type="number" 
                               name="price" 
                               min="1"
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                               required>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                @click="open = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600">
                            List Pack
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

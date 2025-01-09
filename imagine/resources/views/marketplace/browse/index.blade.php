<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Marketplace Stats -->
            <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 mb-1">Total Listings</div>
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($marketplaceStats['total_listings']) }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 mb-1">Average Price</div>
                    <div class="text-2xl font-bold text-purple-600">{{ number_format($marketplaceStats['avg_price']) }} PULSE</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 mb-1">Total Volume</div>
                    <div class="text-2xl font-bold text-green-600">{{ number_format($marketplaceStats['total_volume']) }} PULSE</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 mb-1">Your Balance</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ number_format(Auth::user()->getCreditBalance()) }} PULSE</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Filter Form -->
                    <form id="filter-form" class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Min Price (PULSE)
                            </label>
                            <input type="number" 
                                   name="min_price" 
                                   id="min_price"
                                   min="0"
                                   value="{{ $filters['min_price'] ?? '' }}"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Max Price (PULSE)
                            </label>
                            <input type="number" 
                                   name="max_price" 
                                   id="max_price"
                                   min="0"
                                   value="{{ $filters['max_price'] ?? '' }}"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                        <div>
                            <label for="min_cards" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Min Cards
                            </label>
                            <input type="number" 
                                   name="min_cards" 
                                   id="min_cards"
                                   min="1"
                                   value="{{ $filters['min_cards'] ?? '' }}"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Sort By
                            </label>
                            <select name="sort" 
                                    id="sort"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="latest" {{ ($filters['sort'] ?? '') === 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_asc" {{ ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                                <option value="price_desc" {{ ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                                <option value="cards_asc" {{ ($filters['sort'] ?? '') === 'cards_asc' ? 'selected' : '' }}>Cards (Low to High)</option>
                                <option value="cards_desc" {{ ($filters['sort'] ?? '') === 'cards_desc' ? 'selected' : '' }}>Cards (High to Low)</option>
                            </select>
                        </div>
                    </form>

                    <!-- Recent Sales -->
                    @if($marketplaceStats['recent_sales']->isNotEmpty())
                        <div class="mb-8">
                            <h3 class="text-lg font-medium mb-4">Recent Sales</h3>
                            <div class="flex space-x-4 overflow-x-auto pb-4">
                                @foreach($marketplaceStats['recent_sales'] as $sale)
                                    <div class="flex-shrink-0 bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                        <div class="text-sm text-gray-500">{{ $sale->created_at->diffForHumans() }}</div>
                                        <div class="font-medium">{{ number_format($sale->amount) }} PULSE</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Pack Grid -->
                    <div id="pack-grid-container">
                        @include('marketplace.browse.components.pack-grid', ['availablePacks' => $availablePacks])
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

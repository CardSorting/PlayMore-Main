@props(['filters', 'activeTab', 'totalItems'])

<div class="py-6 space-y-4">
    <!-- Search Bar -->
    <div class="max-w-lg">
        <form action="{{ request()->url() }}" method="GET">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ $filters->search }}"
                       placeholder="Search {{ $activeTab }}..."
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
        </form>
    </div>

    <!-- Filters and Sorting -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <!-- Active Filters -->
        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-700">
                {{ $totalItems }} {{ $activeTab }}
            </span>
            
            @if($filters->search)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Search: {{ $filters->search }}
                    <a href="{{ request()->url() }}?tab={{ $activeTab }}&sort={{ request('sort', 'newest') }}" class="ml-1 text-blue-600 hover:text-blue-800">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </span>
            @endif
        </div>

        <!-- Sort and Price Filter -->
        <div class="flex items-center space-x-4">
            <!-- Price Range Filter -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                    </svg>
                    Price Range
                </button>

                <div x-show="open" 
                     @click.away="open = false"
                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-10">
                    <form action="{{ request()->url() }}" method="GET" class="p-4">
                        <input type="hidden" name="tab" value="{{ $activeTab }}">
                        <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">
                        <input type="hidden" name="search" value="{{ $filters->search }}">
                        
                        <div class="space-y-4">
                            <div>
                                <label for="price_min" class="block text-sm font-medium text-gray-700">Min Price</label>
                                <input type="number" 
                                       name="price_min" 
                                       id="price_min"
                                       min="{{ $filters->priceRanges->min_price }}"
                                       max="{{ $filters->priceRanges->max_price }}"
                                       value="{{ $filters->priceMin }}"
                                       step="0.01"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="price_max" class="block text-sm font-medium text-gray-700">Max Price</label>
                                <input type="number"
                                       name="price_max"
                                       id="price_max"
                                       min="{{ $filters->priceRanges->min_price }}"
                                       max="{{ $filters->priceRanges->max_price }}"
                                       value="{{ $filters->priceMax }}"
                                       step="0.01"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sort Dropdown -->
            <form class="flex items-center" x-data="{ sort: '{{ request('sort', 'newest') }}' }">
                <label for="sort" class="text-sm font-medium text-gray-700 mr-2">Sort by:</label>
                <select id="sort" 
                        name="sort" 
                        x-model="sort"
                        @change="window.location = '{{ request()->url() }}?tab={{ $activeTab }}&sort=' + sort + '&search={{ $filters->search }}&price_min={{ $filters->priceMin }}&price_max={{ $filters->priceMax }}'"
                        class="text-sm border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @foreach($filters->getSortOptions() as $value => $label)
                        <option value="{{ $value }}" @selected($filters->sort === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
</div>

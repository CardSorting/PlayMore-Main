<div class="bg-white p-4 rounded-lg shadow-sm mb-6 space-y-4">
    <div class="flex flex-col sm:flex-row justify-between gap-4">
        <!-- Sort Controls -->
        <div class="flex items-center space-x-4">
            <label for="sort" class="text-sm font-medium text-gray-700">Sort by:</label>
            <select id="sort" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <option value="name">Name</option>
                <option value="rarity">Rarity</option>
                <option value="type">Type</option>
            </select>
            <button onclick="toggleSortDirection()" class="p-2 rounded-md hover:bg-gray-100" aria-label="Toggle sort direction">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                </svg>
            </button>
        </div>

        <!-- Search Bar -->
        <div class="relative">
            <input type="text" 
                   id="cardSearch" 
                   placeholder="Search cards..." 
                   class="w-64 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   onkeyup="searchCards(this.value)">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="space-y-4">
        <!-- Rarity Filters -->
        <div>
            <h4 class="text-sm font-medium text-gray-700 mb-2">Rarity</h4>
            <div class="flex flex-wrap gap-2">
                @foreach($rarityFilters as $key => $filter)
                    <button onclick="filterByRarity('{{ $key }}')" 
                            class="filter-btn px-3 py-1 rounded-full text-sm font-medium {{ $filter['bg'] }} {{ $filter['text'] }} {{ $filter['hover'] }} {{ $key === 'all' ? 'active' : '' }}">
                        {{ $filter['label'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Type Filters -->
        <div>
            <h4 class="text-sm font-medium text-gray-700 mb-2">Card Type</h4>
            <div class="flex flex-wrap gap-2">
                @foreach($typeFilters as $key => $filter)
                    <button onclick="filterByType('{{ $key }}')" 
                            class="type-filter-btn px-3 py-1 rounded-full text-sm font-medium {{ $filter['bg'] }} {{ $filter['text'] }} {{ $filter['hover'] }} {{ $key === 'all' ? 'active' : '' }}">
                        {{ $filter['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentSort = { field: 'name', ascending: true };
    let currentFilter = 'all';
    let currentTypeFilter = 'all';

    function toggleSortDirection() {
        currentSort.ascending = !currentSort.ascending;
        sortCards();
    }

    function filterByRarity(rarity) {
        currentFilter = rarity;
        updateFilterButtons('filter-btn', rarity);
        applyFilters();
    }

    function filterByType(type) {
        currentTypeFilter = type;
        updateFilterButtons('type-filter-btn', type);
        applyFilters();
    }

    function updateFilterButtons(buttonClass, value) {
        document.querySelectorAll('.' + buttonClass).forEach(btn => {
            btn.classList.remove('active', 'bg-blue-100', 'text-blue-800');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        });
        
        const activeBtn = document.querySelector(`[onclick="${buttonClass === 'filter-btn' ? 'filterByRarity' : 'filterByType'}('${value}')"]`);
        if (activeBtn) {
            activeBtn.classList.add('active');
            activeBtn.classList.remove('bg-gray-100', 'text-gray-700');
        }
    }

    function searchCards(query) {
        query = query.toLowerCase();
        document.querySelectorAll('.card-item').forEach(card => {
            const name = card.dataset.name;
            const type = card.dataset.type;
            const matches = name.includes(query) || type.includes(query);
            card.style.display = matches ? (window.currentView === 'grid' ? 'block' : 'flex') : 'none';
        });
        
        if (window.currentView === 'grid' && window.masonryInstance) {
            window.masonryInstance.layout();
        }
    }

    // Initialize event listeners
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('sort').addEventListener('change', (e) => {
            currentSort.field = e.target.value;
            sortCards();
        });
    });
</script>
@endpush

@if($availablePacks->isEmpty())
    <div class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No Packs Available</h3>
        <p class="mt-1 text-sm text-gray-500">No packs match your current filters.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="pack-grid">
        @foreach($availablePacks as $pack)
            <div class="pack-item" data-price="{{ $pack->price }}" data-cards="{{ $pack->cards_count }}">
                <x-marketplace.browse.available-pack-card :pack="$pack" />
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $availablePacks->links() }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filter-form');
            const packGrid = document.getElementById('pack-grid');
            const paginationContainer = document.getElementById('pagination-container');

            if (filterForm) {
                filterForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    await updateResults();
                });

                // Debounced input handling
                const inputs = filterForm.querySelectorAll('input, select');
                inputs.forEach(input => {
                    input.addEventListener('change', debounce(updateResults, 500));
                });
            }

            async function updateResults() {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                
                try {
                    // Show loading state
                    packGrid.classList.add('opacity-50');
                    
                    const response = await fetch(`/marketplace/browse/filter?${params.toString()}`);
                    const data = await response.json();
                    
                    // Update grid and pagination
                    packGrid.innerHTML = data.html;
                    if (paginationContainer) {
                        paginationContainer.innerHTML = data.pagination;
                    }
                    
                    // Update URL without page reload
                    window.history.pushState({}, '', `?${params.toString()}`);
                } catch (error) {
                    console.error('Error updating results:', error);
                } finally {
                    // Remove loading state
                    packGrid.classList.remove('opacity-50');
                }
            }

            // Handle pagination clicks
            document.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    const page = url.searchParams.get('page');
                    if (page && filterForm) {
                        const formData = new FormData(filterForm);
                        formData.set('page', page);
                        const params = new URLSearchParams(formData);
                        updateResults(params);
                    }
                }
            });

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        });
    </script>
@endif

@props(['items'])

<div class="py-6">
    @if($items->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No items found</h3>
            <p class="mt-1 text-sm text-gray-500">
                @if(request('search'))
                    No results found for "{{ request('search') }}". Try adjusting your search terms.
                @else
                    No items available at this time. Check back later!
                @endif
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
            @foreach ($items as $item)
                <x-store.gallery-item :gallery="$item" />
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="py-8">
            {{ $items->links() }}
        </div>
    @endif
</div>

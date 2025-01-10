@props(['activeTab', 'totalPrints', 'totalCards'])

<div class="border-b border-gray-200">
    <nav class="-mb-px flex space-x-8" aria-label="Store sections">
        <a href="?tab=prints{{ request('sort') ? '&sort=' . request('sort') : '' }}" 
           class="{{ $activeTab === 'prints' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
            Prints
            <span class="ml-2 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">{{ $totalPrints }}</span>
        </a>

        <a href="?tab=cards{{ request('sort') ? '&sort=' . request('sort') : '' }}" 
           class="{{ $activeTab === 'cards' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
            Cards
            <span class="ml-2 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">{{ $totalCards }}</span>
        </a>
    </nav>
</div>

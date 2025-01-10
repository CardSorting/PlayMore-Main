<div class="border-b border-gray-200 mb-4">
    <nav class="-mb-px flex space-x-8" aria-label="Gallery Actions">
        <a href="{{ route('images.gallery', ['tab' => 'create']) }}" 
           class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm {{ request('tab', 'create') === 'create' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Create Cards
        </a>
        <a href="{{ route('images.gallery', ['tab' => 'print']) }}" 
           class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm {{ request('tab') === 'print' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Order Prints
        </a>
    </nav>
</div>

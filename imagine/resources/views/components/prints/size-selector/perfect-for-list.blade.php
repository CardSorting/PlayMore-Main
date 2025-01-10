@props(['category'])

<div class="mt-8 bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 shadow-sm ring-1 ring-gray-900/5">
    <div class="flex items-center space-x-2">
        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h4 class="font-medium text-gray-900">Perfect For</h4>
    </div>
    <ul class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">
        @if($category === 'Mini Prints')
            @foreach(['Trading cards and collectibles', 'Business cards', 'Small keepsakes', 'Mini art prints'] as $item)
                <x-prints.size-selector.perfect-for-item :text="$item" />
            @endforeach
        @elseif($category === 'Photo Prints')
            @foreach(['Photo prints and postcards', 'Desk displays', 'Small artwork', 'Standard frames'] as $item)
                <x-prints.size-selector.perfect-for-item :text="$item" />
            @endforeach
        @elseif($category === 'Wall Art')
            @foreach(['Living room displays', 'Office artwork', 'Gallery walls', 'Statement pieces'] as $item)
                <x-prints.size-selector.perfect-for-item :text="$item" />
            @endforeach
        @elseif($category === 'Gallery Prints')
            @foreach(['Exhibition displays', 'Commercial spaces', 'Large walls', 'Professional installations'] as $item)
                <x-prints.size-selector.perfect-for-item :text="$item" />
            @endforeach
        @endif
    </ul>
</div>

@props(['current-step'])

@php
$steps = [
    1 => [
        'name' => 'Size',
        'description' => 'Choose print size'
    ],
    2 => [
        'name' => 'Material',
        'description' => 'Select material finish'
    ],
    3 => [
        'name' => 'Checkout',
        'description' => 'Review and pay'
    ]
];
@endphp

<nav aria-label="Progress">
    <ol role="list" class="space-y-4 md:flex md:space-x-8 md:space-y-0">
        @foreach ($steps as $step => $details)
            <li class="md:flex-1">
                <div class="group flex flex-col border-l-4 border-indigo-600 py-2 pl-4 md:border-l-0 md:border-t-4 md:pb-0 md:pl-0 md:pt-4
                    {{ $step < $current-step ? 'border-indigo-600' : ($step === $current-step ? 'border-indigo-600' : 'border-gray-200') }}">
                    <span class="text-sm font-medium
                        {{ $step < $current-step ? 'text-indigo-600' : ($step === $current-step ? 'text-indigo-600' : 'text-gray-500') }}">
                        Step {{ $step }}
                    </span>
                    <span class="text-sm font-medium
                        {{ $step < $current-step ? 'text-gray-900' : ($step === $current-step ? 'text-gray-900' : 'text-gray-500') }}">
                        {{ $details['name'] }}
                    </span>
                    <span class="text-sm font-medium
                        {{ $step < $current-step ? 'text-gray-500' : ($step === $current-step ? 'text-gray-500' : 'text-gray-500') }}">
                        {{ $details['description'] }}
                    </span>
                </div>
            </li>
        @endforeach
    </ol>
</nav>

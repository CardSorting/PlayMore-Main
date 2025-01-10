@props(['currentStep' => 1])

@php
$steps = [
    1 => ['title' => 'Details', 'description' => 'Choose size and shipping'],
    2 => ['title' => 'Payment', 'description' => 'Complete payment'],
    3 => ['title' => 'Confirmation', 'description' => 'Order confirmed']
];
@endphp

<nav aria-label="Order Progress" class="border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ol role="list" class="flex items-center justify-center space-x-16 py-4">
            @foreach($steps as $step => $details)
                <li class="flex items-center {{ $step < $currentStep ? 'text-green-600' : ($step === $currentStep ? 'text-blue-600' : 'text-gray-400') }}">
                    <span class="relative flex items-center justify-center">
                        @if($step < $currentStep)
                            <span class="relative flex h-8 w-8 items-center justify-center rounded-full bg-green-600">
                                <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @elseif($step === $currentStep)
                            <span class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-blue-600 bg-white">
                                <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                            </span>
                        @else
                            <span class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white">
                                <span class="text-sm">{{ $step }}</span>
                            </span>
                        @endif
                    </span>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ $details['title'] }}</p>
                        <p class="text-xs text-gray-500 hidden sm:block">{{ $details['description'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</nav>

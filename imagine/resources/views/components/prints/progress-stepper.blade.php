@props(['currentStep' => 1])

@php
$steps = [
    1 => [
        'title' => 'Customize',
        'description' => 'Choose size and options',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />'
    ],
    2 => [
        'title' => 'Review',
        'description' => 'Review order details',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />'
    ],
    3 => [
        'title' => 'Payment',
        'description' => 'Complete payment',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />'
    ]
];
@endphp

<nav aria-label="Order Progress" class="py-4">
    <ol role="list" class="flex items-center justify-center space-x-8">
        @foreach($steps as $step => $details)
            <li class="relative flex items-center {{ $step < $currentStep ? 'text-blue-600' : ($step === $currentStep ? 'text-blue-600' : 'text-gray-400') }}">
                <!-- Step Indicator -->
                <div class="flex items-center">
                    @if($step < $currentStep)
                        <!-- Completed Step -->
                        <span class="relative flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 group-hover:bg-blue-800">
                            <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    @elseif($step === $currentStep)
                        <!-- Current Step -->
                        <span class="relative flex h-10 w-10 items-center justify-center rounded-full border-2 border-blue-600 bg-white">
                            <svg class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                {!! $details['icon'] !!}
                            </svg>
                        </span>
                    @else
                        <!-- Upcoming Step -->
                        <span class="relative flex h-10 w-10 items-center justify-center rounded-full border-2 border-gray-300 bg-white">
                            <svg class="h-6 w-6 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                {!! $details['icon'] !!}
                            </svg>
                        </span>
                    @endif

                    <!-- Step Text -->
                    <div class="ml-4 hidden md:block">
                        <p class="text-sm font-medium {{ $step <= $currentStep ? 'text-blue-600' : 'text-gray-900' }}">
                            {{ $details['title'] }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $details['description'] }}</p>
                    </div>
                </div>

                <!-- Connector Line -->
                @if($step < count($steps))
                    <div class="hidden md:block absolute top-5 left-full h-0.5 w-16 {{ $step < $currentStep ? 'bg-blue-600' : 'bg-gray-300' }}"></div>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

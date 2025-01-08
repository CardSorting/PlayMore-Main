<div class="bg-white p-6 rounded-lg shadow-sm mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($stats as $key => $stat)
            <div class="bg-gradient-to-br {{ $stat['gradient'] }} p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-{{ $stat['color'] }}-600 text-sm font-medium">{{ $stat['label'] }}</p>
                        <h4 class="text-2xl font-bold text-{{ $stat['color'] }}-800">{{ $stat['count'] }}</h4>
                    </div>
                    <div class="w-3 h-3 rounded-full {{ $stat['dot'] }}"></div>
                </div>
            </div>
        @endforeach
    </div>

    @if($slot->isNotEmpty())
        <div class="mt-4 pt-4 border-t border-gray-200">
            {{ $slot }}
        </div>
    @endif
</div>

@once
    @push('styles')
    <style>
        .text-gold-600 { color: #B7791F; }
        .text-gold-800 { color: #975A16; }
        .bg-gold-400 { background-color: #D69E2E; }
    </style>
    @endpush
@endonce

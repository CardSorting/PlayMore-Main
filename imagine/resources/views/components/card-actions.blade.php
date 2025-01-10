<div class="flex space-x-2">
    <!-- Info Button -->
    <button wire:click="toggleDetails" 
            class="bg-gray-800/90 text-white p-2 rounded-full hover:bg-gray-700 transition-all duration-200 shadow-lg
                   transform hover:scale-110 hover:rotate-12
                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="sr-only">Show Details</span>
    </button>

    <!-- Flip Button -->
    <button wire:click="flipCard" 
            class="bg-purple-600/90 text-white p-2 rounded-full hover:bg-purple-500 transition-all duration-200 shadow-lg
                   transform hover:scale-110 hover:-rotate-12
                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500
                   {{ $showFlipAnimation ? 'rotate-180' : '' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        <span class="sr-only">Flip Card</span>
    </button>

    <!-- Audio Effect -->
    <audio id="card-flip-sound" preload="auto" class="hidden">
        <source src="{{ asset('audio/card-flip.mp3') }}" type="audio/mpeg">
    </audio>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const flipSound = document.getElementById('card-flip-sound');
    
    Livewire.on('cardFlipped', () => {
        if (flipSound) {
            flipSound.currentTime = 0;
            flipSound.play().catch(error => {
                console.log('Audio playback prevented:', error);
            });
        }
    });
});
</script>
@endpush

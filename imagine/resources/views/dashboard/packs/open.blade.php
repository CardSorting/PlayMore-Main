<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-bold mb-4">Opening {{ $pack->name }}</h2>
                    
                    <div class="pack-opening-container">
                        <div class="pack-cards-grid">
                            @foreach($cards as $index => $card)
                                <div class="card-reveal-container" data-index="{{ $index }}" 
                                     x-data="{ revealed: false }" 
                                     @click="if (!revealed) { 
                                         revealed = true; 
                                         const audio = new Audio('/audio/card-flip.mp3');
                                         audio.volume = 0.3;
                                         audio.play().catch(() => {});
                                     }">
                                    <div class="card-inner" :class="{ 'rotate-y-180': revealed }" style="transform-style: preserve-3d">
                                        <div class="card-back absolute w-full h-full backface-hidden">
                                            <div class="card-back-design"></div>
                                        </div>
                                        <div class="card-front absolute w-full h-full backface-hidden rotate-y-180">
                                            <livewire:card-display :card="$card" :wire:key="'card-'.$card->name" />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 text-center reveal-all-container">
                            <button type="button" 
                                    onclick="revealAllCards()"
                                    class="reveal-all-btn px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Reveal All Cards
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .backface-hidden {
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }

        .pack-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .card-reveal-container {
            perspective: 1000px;
            aspect-ratio: 63/88;
            min-height: 350px;
            cursor: pointer;
        }

        /* Ensure Livewire component fills card space */
        .card-front :deep(.mtg-card) {
            height: 100% !important;
            width: 100% !important;
        }

        .card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform 0.8s cubic-bezier(0.645, 0.045, 0.355, 1);
        }

        .card-back, .card-front {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 0.5rem;
        }

        .card-back {
            background: linear-gradient(45deg, #1a1a1a, #2a2a2a);
            border: 2px solid #333;
            overflow: hidden;
        }

        .card-back::before {
            content: '';
            position: absolute;
            inset: 4px;
            border: 1px solid rgba(255, 215, 0, 0.2);
            border-radius: 0.25rem;
        }

        .card-back-design {
            position: absolute;
            inset: 0;
            background: 
                radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.2) 100%),
                repeating-linear-gradient(45deg, 
                    rgba(255,255,255,0.1) 0px, 
                    rgba(255,255,255,0.1) 2px,
                    transparent 2px, 
                    transparent 4px
                );
        }

        .card-back-design::before {
            content: '';
            position: absolute;
            inset: 15px;
            border: 2px solid rgba(255, 215, 0, 0.15);
            border-radius: 0.25rem;
            background: radial-gradient(
                circle at center,
                rgba(255, 215, 0, 0.1) 0%,
                transparent 70%
            );
        }

        .card-back-design::after {
            content: '';
            position: absolute;
            inset: 20px;
            background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 0L40 20L20 40L0 20L20 0z' fill='rgba(255, 215, 0, 0.1)'/%3E%3C/svg%3E");
            background-size: 20px 20px;
            opacity: 0.3;
        }

        /* Enhanced hover effect */
        @media (hover: hover) {
            .card-reveal-container:not(.revealed):hover .card-back {
                box-shadow: 0 0 15px rgba(255, 215, 0, 0.2);
            }
            .card-reveal-container:not(.revealed):hover .card-back-design::before {
                border-color: rgba(255, 215, 0, 0.3);
            }
        }

        .card-front {
            transform: rotateY(180deg);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                       0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .card-reveal-container.revealed .card-inner {
            transform: rotateY(180deg);
        }

        /* Hover effect only on non-touch devices */
        @media (hover: hover) {
            .card-reveal-container:not(.revealed):hover .card-inner {
                transform: rotateY(15deg);
            }
        }

        /* Animation for revealing cards */
        @keyframes floatIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-reveal-container {
            animation: floatIn 0.5s ease-out backwards;
        }

        /* Stagger the float-in animation for each card */
        .card-reveal-container:nth-child(1) { animation-delay: 0.1s; }
        .card-reveal-container:nth-child(2) { animation-delay: 0.2s; }
        .card-reveal-container:nth-child(3) { animation-delay: 0.3s; }
        .card-reveal-container:nth-child(4) { animation-delay: 0.4s; }
        .card-reveal-container:nth-child(5) { animation-delay: 0.5s; }
        .card-reveal-container:nth-child(6) { animation-delay: 0.6s; }
        .card-reveal-container:nth-child(7) { animation-delay: 0.7s; }
        .card-reveal-container:nth-child(8) { animation-delay: 0.8s; }
        .card-reveal-container:nth-child(9) { animation-delay: 0.9s; }
        .card-reveal-container:nth-child(10) { animation-delay: 1.0s; }

        /* Shine effect on card back */
        .card-back::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.1) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(-45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) rotate(-45deg);
            }
            100% {
                transform: translateX(100%) rotate(-45deg);
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.revealAllCards = async function() {
                const containers = document.querySelectorAll('.card-reveal-container');
                const revealAllBtn = document.querySelector('.reveal-all-btn');
                
                revealAllBtn.disabled = true;

                for (let container of containers) {
                    const cardComponent = Alpine.data(container);
                    if (!cardComponent.revealed) {
                        cardComponent.revealed = true;
                        // Play a subtle sound effect
                        const audio = new Audio('/audio/card-flip.mp3');
                        audio.volume = 0.3;
                        audio.play().catch(() => {}); // Ignore errors if audio can't play
                        await new Promise(resolve => setTimeout(resolve, 200));
                    }
                }

                // After all cards are revealed, show success message and redirect
                setTimeout(() => {
                    window.location.href = '{{ route('cards.index') }}?opened=true';
                }, 1500);
            };
        });
    </script>
    @endpush
</x-app-layout>

@extends('layouts.cards')

@section('header')
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Card Collection</h1>
        </div>
    </div>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
        @if($cards->isEmpty())
            <x-empty-state type="cards" />
        @else
            <!-- Collection Header -->
            <div class="bg-white sticky top-0 z-10 p-4 mb-6 rounded-lg shadow-sm border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">Your Collection</h2>
                    <span class="text-sm text-gray-600" id="cardCount">
                        {{ $cards->total() }} cards
                    </span>
                </div>
            </div>

            <!-- View Controls -->
            <div class="flex justify-end mb-4 space-x-2">
                <button class="view-btn p-2 rounded-lg hover:bg-gray-100 transition-colors" data-view="grid">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </button>
                <button class="view-btn p-2 rounded-lg hover:bg-gray-100 transition-colors" data-view="list">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Card Views Container -->
            <div class="relative bg-gradient-to-br from-gray-50 via-white to-gray-50 p-6 rounded-xl
                        shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)]
                        before:absolute before:inset-0 before:bg-[radial-gradient(circle_at_50%_0,rgba(255,255,255,0.8),transparent_50%)]
                        before:pointer-events-none before:opacity-70
                        after:absolute after:inset-0 after:bg-[radial-gradient(circle_at_50%_100%,rgba(0,0,0,0.05),transparent_50%)]
                        after:pointer-events-none after:opacity-50">
                
                <!-- Grid View -->
                <div class="w-full">
                    <div id="gridView" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8 relative opacity-0" 
                         x-data="{ hoveredCard: null }">
                        @foreach($cards as $card)
                            <x-card-grid-item :card="$card" />
                        @endforeach
                    </div>
                </div>

                <!-- List View -->
                <div id="listView" class="hidden max-w-6xl mx-auto opacity-0" style="transition: all 0.5s ease-out">
                    <div class="space-y-4 flex flex-col w-full">
                        @foreach($cards as $card)
                            <x-card-list-item :card="$card" class="w-full" />
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-8">
                {{ $cards->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modals -->
<x-card-details-modal />
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize view switching
        const viewButtons = document.querySelectorAll('.view-btn');
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        let currentView = 'grid';

        // Function to switch views
        const switchView = (view) => {
            currentView = view;
            
            // Update button states
            viewButtons.forEach(btn => {
                const isActive = btn.getAttribute('data-view') === view;
                btn.classList.toggle('active', isActive);
                btn.classList.toggle('bg-gray-100', isActive);
            });

            if (view === 'grid') {
                gridView.style.opacity = '1';
                gridView.classList.remove('hidden');
                listView.classList.add('hidden');
                // Show and animate grid cards with stagger
                const cardItems = gridView.querySelectorAll('.card-item');
                cardItems.forEach((card, index) => {
                    setTimeout(() => {
                        card.classList.add('visible');
                    }, index * 100);
                });
                // Initialize 3D effects after cards are visible
                setTimeout(() => {
                    const cards = document.querySelectorAll('.card-container');
                    cards.forEach(card => {
                        new MTGCard3DTiltEffect(card);
                    });
                }, 500);
            } else {
                listView.style.opacity = '1';
                listView.classList.remove('hidden');
                gridView.classList.add('hidden');
                // Show list items
                const listItems = listView.querySelectorAll('.card-item');
                listItems.forEach(item => {
                    item.style.display = 'flex';
                });
            }
        };

        // Set up view button listeners
        viewButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const view = btn.getAttribute('data-view');
                switchView(view);
            });
        });

        // Show initial view after a short delay
        setTimeout(() => switchView('grid'), 150);
    });
</script>
@endpush

@push('styles')
<style>
    /* Card Container */
    .card-container {
        height: 100%;
        transform-style: preserve-3d;
        backface-visibility: hidden;
    }

    /* Card Item */
    .card-item {
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.5s ease-out;
    }

    .card-item.visible {
        transform: translateY(0);
        opacity: 1;
    }

    #listView .card-item {
        width: 100%;
        margin-bottom: 8px;
    }

    /* Grid Layout */
    #gridView {
        display: grid;
        width: 100%;
    }

    #gridView > * {
        width: 100%;
        min-height: 400px;
    }

    @media (max-width: 640px) {
        #gridView {
            grid-template-columns: 1fr;
        }
    }

    @media (min-width: 641px) and (max-width: 1024px) {
        #gridView {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1025px) {
        #gridView {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .card-container {
        aspect-ratio: 2.5/3.5;
    }

    /* Animations */
    @keyframes fadeInScale {
        0% {
            opacity: 0;
            transform: scale(0.95) translateY(10px);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }

    @keyframes rotate-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush

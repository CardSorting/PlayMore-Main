@extends('layouts.gallery')

@section('header')
    <x-gallery-header />
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
            <x-gallery-tabs 
                :images-count="$images->total()"
                :cards-count="$cards->total()"
                :active-tab="request()->query('tab', 'images')"
                class="w-full">
                
                <!-- Images Tab Content -->
                <x-slot name="images">
                    @if($images->isEmpty())
                        <x-empty-state type="images" />
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($images as $image)
                                <x-image-grid-item :image="$image" />
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $images->links() }}
                        </div>
                    @endif
                </x-slot>

                <!-- Cards Tab Content -->
                <x-slot name="cards">
                    @if(isset($cards) && $cards->isNotEmpty())
                        <!-- Main Layout Container -->
                        <div class="w-full">
                            <!-- Main Content -->
                            <div class="w-full">
                                <!-- View Controls -->
                                <div class="bg-white sticky top-0 z-10 p-4 mb-6 rounded-lg shadow-sm border-b border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <h2 class="text-xl font-bold text-gray-900">Your Collection</h2>
                                        <div class="flex items-center space-x-2">
                                            <button class="view-btn p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200" 
                                                    data-view="grid"
                                                    title="Grid View">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                                </svg>
                                            </button>
                                            <button class="view-btn p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200" 
                                                    data-view="list"
                                                    title="List View">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                                </svg>
                                            </button>
                                            <div class="h-6 w-px bg-gray-300 mx-2"></div>
                                            <span class="text-sm text-gray-600" id="cardCount">
                                                {{ $cards->total() }} cards
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Views Container -->
                                <div class="relative bg-gradient-to-br from-gray-50 via-white to-gray-50 p-6 rounded-xl">
                                    <!-- Grid View -->
                                    <div id="gridView" class="cards-masonry relative max-w-7xl mx-auto" 
                                         style="opacity: 0; transition: all 0.5s ease-out;"
                                         x-data="{ hoveredCard: null }">
                                        <!-- Grid sizer for masonry -->
                                        <div class="grid-sizer w-1/3"></div>
                                        @foreach($cards as $card)
                                            <x-card-grid-item :card="$card" />
                                        @endforeach
                                    </div>

                                    <!-- List View -->
                                    <div id="listView" class="hidden space-y-4">
                                        @foreach($cards as $card)
                                            <x-card-list-item :card="$card" />
                                        @endforeach
                                    </div>
                                </div>

                                @if(isset($cards))
                                    <div class="mt-8">
                                        {{ $cards->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <x-empty-state type="cards" />
                    @endif
                </x-slot>
            </x-gallery-tabs>
        </div>
    </div>

    <!-- Modals -->
    <x-image-details-modal />
    <x-card-details-modal />
@endsection

@push('styles')
<style>
    .grid-sizer,
    .card-item {
        width: 100%;
    }

    @media (min-width: 640px) {
        .grid-sizer,
        .card-item {
            width: 50%;
        }
    }

    @media (min-width: 1024px) {
        .grid-sizer,
        .card-item {
            width: 33.333%;
        }
    }

    @keyframes fadeInScale {
        0% {
            opacity: 0;
            transform: scale(0.95);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    .card-item {
        animation-fill-mode: both;
        animation-play-state: paused;
    }

    #gridView {
        perspective: 2000px;
    }

    .cards-masonry {
        margin-left: -12px;
        margin-right: -12px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize services
        const masonryService = new MasonryService();
        const viewService = new ViewService();
        const sortService = new SortService();

        // Initialize masonry layout
        masonryService.initialize();

        // Initialize view service with masonry
        viewService.initialize(masonryService);

        // Initialize sort service with dependencies
        sortService.initialize(masonryService);
        
        // Set up sort direction button
        const sortDirectionBtn = document.getElementById('sortDirection');
        if (sortDirectionBtn) {
            sortDirectionBtn.addEventListener('click', () => {
                sortService.toggleDirection();
            });
        }

        // Set up sort select
        const sortSelect = document.querySelector('[data-sort-control]');
        if (sortSelect) {
            sortSelect.addEventListener('change', (e) => {
                sortService.setField(e.target.value);
            });
        }

        // Load saved preferences
        viewService.loadViewPreference();

        // Initialize 3D effect for cards
        const initializeCardEffects = () => {
            const cards = document.querySelectorAll('.mtg-card');
            cards.forEach(card => {
                new MTGCard3DTiltEffect(card);
            });
        };

        // Show grid and animate cards with stagger
        const gridView = document.getElementById('gridView');
        if (gridView) {
            gridView.style.opacity = '1';
            const cardItems = gridView.querySelectorAll('.card-item');
            cardItems.forEach((card, index) => {
                card.style.animationDelay = `${index * 100}ms`;
                card.style.animationPlayState = 'running';
            });

            // Initialize 3D effects after cards are visible
            setTimeout(initializeCardEffects, cardItems.length * 100 + 500);
        }

        // Re-initialize effects when view changes
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                setTimeout(initializeCardEffects, 300);
            });
        });
    });
</script>
@endpush

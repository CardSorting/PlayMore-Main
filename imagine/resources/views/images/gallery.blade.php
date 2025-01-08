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
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                    </button>
                                    <button class="view-btn p-2 rounded-lg hover:bg-gray-100 transition-colors" data-view="list">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
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
                                    <div id="gridView" class="cards-masonry relative max-w-6xl mx-auto opacity-0" 
                                         style="transition: all 0.5s ease-out;"
                                         x-data="{ hoveredCard: null }">
                                        <!-- Grid sizer for masonry -->
                                        <div class="grid-sizer w-1/3"></div>
                                        @foreach($cards as $card)
                                            <x-card-grid-item :card="$card" />
                                        @endforeach
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
    /* Card Fonts */
    @font-face {
        font-family: 'Beleren';
        src: url('/fonts/Beleren-Bold.woff2') format('woff2');
        font-weight: bold;
        font-style: normal;
        font-display: swap;
    }

    @font-face {
        font-family: 'Matrix';
        src: url('/fonts/Matrix-Regular.woff2') format('woff2');
        font-weight: normal;
        font-style: normal;
        font-display: swap;
    }

    @font-face {
        font-family: 'MPlantin';
        src: url('/fonts/MPlantin-Italic.woff2') format('woff2');
        font-weight: normal;
        font-style: italic;
        font-display: swap;
    }

    .font-beleren {
        font-family: 'Beleren', ui-serif, Georgia, Cambria, serif;
    }

    .font-matrix {
        font-family: 'Matrix', ui-serif, Georgia, Cambria, serif;
    }

    .font-mplantin {
        font-family: 'MPlantin', ui-serif, Georgia, Cambria, serif;
    }

    /* Grid Layout */
    #gridView .card-item {
        width: 100%;
        padding: 12px;
    }

    #listView .card-item {
        width: 100%;
        padding: 12px;
        margin-bottom: 8px;
    }

    @media (min-width: 640px) {
        #gridView .grid-sizer,
        #gridView .card-item {
            width: 50%;
        }
    }

    @media (min-width: 1024px) {
        #gridView .grid-sizer,
        #gridView .card-item {
            width: 33.333%;
        }
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

    .card-item {
        animation-fill-mode: both;
        animation-play-state: paused;
    }

    #gridView {
        perspective: 2000px;
        margin: -12px;
    }

    /* Card Frame Elements */
    .card-frame {
        background-color: #f4e6c7;
        border: 12px solid #171314;
        box-shadow: 
            inset 0 0 0 1px rgba(255, 255, 255, 0.1),
            0 0 15px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
    }

    /* Enhanced Card Layout */
    .card-header {
        flex: 0 0 auto;
        height: 12%;
        min-height: 2.5rem;
        border-bottom: 2px solid #171314;
    }

    .card-art {
        flex: 0 0 45%;
        position: relative;
        margin: 0.75rem;
        border: 4px solid #171314;
        border-radius: 0.375rem;
    }

    .card-type {
        flex: 0 0 auto;
        height: 8%;
        min-height: 2rem;
        margin: 0.75rem;
    }

    .card-text {
        flex: 1 1 auto;
        min-height: 25%;
        margin: 0.75rem;
        border: 2px solid #171314;
        border-radius: 0.375rem;
        background-color: #f4e6c7;
    }

    .card-footer {
        flex: 0 0 auto;
        height: 10%;
        min-height: 2rem;
        margin: 0.75rem;
        border-radius: 0.375rem;
        background-color: #171314;
    }

    /* Scrollbar Styling */
    .scrollbar-thin::-webkit-scrollbar {
        width: 4px;
    }

    .scrollbar-thin::-webkit-scrollbar-track {
        background: transparent;
    }

    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: rgba(23, 19, 20, 0.2);
        border-radius: 2px;
    }

    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: rgba(23, 19, 20, 0.3);
    }

    /* Enhanced Text Styling */
    .abilities-text {
        font-family: 'Matrix', ui-serif, Georgia, Cambria, serif;
        line-height: 1.5;
        color: #171314;
    }

    .flavor-text {
        font-family: 'MPlantin', ui-serif, Georgia, Cambria, serif;
        font-style: italic;
        line-height: 1.5;
        color: rgba(23, 19, 20, 0.9);
    }

    /* Card Name Styling */
    .card-name {
        font-family: 'Beleren', ui-serif, Georgia, Cambria, serif;
        font-weight: bold;
        letter-spacing: 0.05em;
        color: #e6e3de;
        text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.4);
    }


    /* Grid Layout Refinements */
    .grid-sizer,
    .card-item {
        width: 100%;
        padding: 16px;
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

    #gridView {
        perspective: 2000px;
        margin: -16px;
    }

    /* Enhanced Type Border */
    .clip-type-border::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to right,
            transparent,
            rgba(255, 255, 255, 0.1) 50%,
            transparent
        );
        clip-path: inherit;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize services
        const masonryService = new MasonryService();
        const sortService = new SortService();
        const viewService = new ViewService();

        // Initialize masonry layout
        masonryService.initialize();

        // Initialize sort service
        sortService.initialize(masonryService);

        // Initialize filter service
        const filterService = new FilterService();
        filterService.initialize(masonryService);

        // Initialize view service
        viewService.initialize(masonryService, filterService);
        
        // Load saved view preference
        viewService.loadViewPreference();
        
        // Set initial active state on view buttons
        const currentView = viewService.getCurrentView();
        document.querySelectorAll('.view-btn').forEach(btn => {
            const isActive = btn.getAttribute('data-view') === currentView;
            btn.classList.toggle('active', isActive);
            btn.classList.toggle('bg-gray-100', isActive);
        });
        
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

        // Initialize 3D effect for cards
        const initializeCardEffects = () => {
            const cards = document.querySelectorAll('.card-container');
            cards.forEach(card => {
                new MTGCard3DTiltEffect(card);
            });
        };

        // Show initial view
        const showInitialView = () => {
            const currentView = viewService.getCurrentView();
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');

            if (currentView === 'grid') {
                gridView.style.opacity = '1';
                listView.classList.add('hidden');
                // Show and animate grid cards with stagger
                const cardItems = gridView.querySelectorAll('.card-item');
                cardItems.forEach((card, index) => {
                    card.style.animationDelay = `${index * 100}ms`;
                    card.style.animationPlayState = 'running';
                });
                // Initialize 3D effects after cards are visible
                setTimeout(initializeCardEffects, cardItems.length * 100 + 500);
            } else {
                listView.style.opacity = '1';
                gridView.classList.add('hidden');
                // Show list items
                const listItems = listView.querySelectorAll('.card-item');
                listItems.forEach(item => {
                    item.style.display = 'flex';
                });
            }
        };

        // Show initial view after a short delay to ensure transitions work
        setTimeout(showInitialView, 150);
    });
</script>
@endpush

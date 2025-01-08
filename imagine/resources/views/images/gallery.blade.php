<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Gallery') }}
            </h2>
            <a href="{{ route('images.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Generate New Image
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tabs Navigation -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
                <nav class="flex" aria-label="Gallery Sections">
                    <button onclick="switchTab('images')" 
                            id="images-tab-button"
                            class="flex-1 inline-flex items-center justify-center py-4 px-6 border-b-2 text-sm font-medium focus:outline-none" 
                            data-tab="images"
                            role="tab"
                            aria-selected="true"
                            aria-controls="images-tab">
                        <svg class="mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Generated Images
                        <span class="ml-3 bg-gray-100 py-0.5 px-2.5 rounded-full text-xs font-medium text-gray-600 md:inline-block">
                            {{ $images->total() }}
                        </span>
                    </button>
                    <button onclick="switchTab('cards')" 
                            id="cards-tab-button"
                            class="flex-1 inline-flex items-center justify-center py-4 px-6 border-b-2 text-sm font-medium focus:outline-none" 
                            data-tab="cards"
                            role="tab"
                            aria-selected="false"
                            aria-controls="cards-tab">
                        <svg class="mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Created Cards
                        <span class="ml-3 bg-gray-100 py-0.5 px-2.5 rounded-full text-xs font-medium text-gray-600 md:inline-block">
                            {{ $cards->total() }}
                        </span>
                    </button>
                </nav>
            </div>

            <!-- Images Tab Content -->
            <div id="images-tab" 
                 class="tab-content"
                 role="tabpanel"
                 aria-labelledby="images-tab-button"
                 tabindex="0">
                @if($images->isEmpty())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-50 rounded-lg mb-6">
                                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Images Yet</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto">Start your creative journey by generating your first AI image!</p>
                            <a href="{{ route('images.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Generate First Image
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($images as $image)
                        <div>
                            <!-- Image Container -->
                            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                                <div class="image-container relative h-64 w-full cursor-pointer rounded-lg overflow-hidden" 
                                     onclick="showDetails('{{ $image->prompt }}', '{{ $image->image_url }}', '{{ $image->aspect_ratio }}', '{{ $image->process_mode }}', '{{ $image->task_id }}')"
                                     role="button"
                                     tabindex="0"
                                     aria-label="View details for image: {{ $image->prompt }}">
                                    <img src="{{ $image->image_url }}" 
                                         alt="{{ $image->prompt }}"
                                         class="w-full h-full object-cover rounded-lg"
                                    >
                                    
                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-200 rounded-lg">
                                        <div class="absolute inset-0 flex flex-col items-center justify-center p-6">
                                            <p class="text-white text-center mb-6 line-clamp-3 text-shadow-lg font-medium">{{ $image->prompt }}</p>
                                            <div class="flex space-x-3">
                                                <a href="{{ $image->image_url }}" 
                                                   download 
                                                   target="_blank"
                                                   class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                   onclick="event.stopPropagation()"
                                                   aria-label="Download image"
                                                >
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Download
                                                </a>
                                                <button class="inline-flex items-center px-4 py-2 bg-gray-800 text-white font-medium rounded-md hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                                        aria-label="View image details">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <!-- Create Card Button -->
                                <div class="p-2 bg-gray-50 border-t">
                                    <a href="{{ route('images.create-card', $image->id) }}"
                                       class="flex items-center justify-center px-3 py-1.5 bg-purple-500 text-black text-sm font-medium rounded hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                                       aria-label="Create card from this image"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <span>Create Card</span>
                                    </a>
                                </div>
                        </div>
                    @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $images->links() }}
                    </div>
                @endif
            </div>

            <!-- Cards Tab Content -->
            <div id="cards-tab" 
                 class="tab-content hidden"
                 role="tabpanel"
                 aria-labelledby="cards-tab-button"
                 tabindex="0">
                @if(isset($cards) && $cards->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($cards as $card)
                            <div>
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                                        <div class="mtg-card w-full aspect-[2.5/3.5] relative rounded-lg overflow-hidden shadow-xl transition-all duration-500 hover:scale-105 hover:shadow-[0_0_30px_rgba(255,215,0,0.3)]" 
                                             style="transform-style: preserve-3d; perspective: 1000px;"
                                             data-rarity="{{ $card['rarity'] }}">
                                        <!-- Holographic Overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 pointer-events-none z-10"></div>
                                        
                                        <div class="card-frame h-full p-3 flex flex-col bg-gradient-to-b from-[#f8f8f8] to-[#e8e8e8] border border-gray-300">
                                            <!-- Card Header -->
                                            <div class="card-header flex justify-between items-center bg-gradient-to-r from-[#e9e5cd] to-[#f5f1e6] p-2 rounded-lg mb-1 shadow-sm border border-gray-200">
                                                <h2 class="card-name text-lg font-bold" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                                                    {{ $card['name'] }}
                                                </h2>
                                                <div class="mana-cost flex space-x-1">
                                                    @if(isset($card['mana_cost']))
                                                        @foreach(explode(',', $card['mana_cost']) as $symbol)
                                                            <div class="mana-symbol rounded-full flex justify-center items-center text-xs font-bold w-6 h-6 shadow-lg
                                                                @if(strtolower($symbol) == 'w') bg-gradient-to-br from-yellow-100 to-yellow-300 text-black border-2 border-yellow-400
                                                                @elseif(strtolower($symbol) == 'u') bg-gradient-to-br from-blue-400 to-blue-600 text-white border-2 border-blue-300
                                                                @elseif(strtolower($symbol) == 'b') bg-gradient-to-br from-gray-800 to-black text-white border-2 border-gray-600
                                                                @elseif(strtolower($symbol) == 'r') bg-gradient-to-br from-red-400 to-red-600 text-white border-2 border-red-300
                                                                @elseif(strtolower($symbol) == 'g') bg-gradient-to-br from-green-400 to-green-600 text-white border-2 border-green-300
                                                                @else bg-gradient-to-br from-gray-300 to-gray-500 text-black border-2 border-gray-400
                                                                @endif">
                                                                {{ $symbol }}
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Card Image -->
                                            <div class="relative rounded-lg overflow-hidden mb-1 shadow-lg">
                                                <img src="{{ $card['image_url'] }}" 
                                                     alt="{{ $card['name'] }}" 
                                                     class="w-full h-[140px] object-cover object-center transform hover:scale-105 transition-transform duration-500">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                                            </div>

                                            <!-- Card Type -->
                                            <div class="card-type bg-gradient-to-r from-[#e9e5cd] to-[#f5f1e6] p-1.5 text-xs border border-gray-200 rounded-md mb-1 font-semibold shadow-sm">
                                                {{ $card['card_type'] }}
                                            </div>

                                            <!-- Card Text -->
                                            <div class="card-text bg-[#f5f1e6] rounded-lg flex-grow overflow-y-auto p-2 shadow-inner border border-gray-200 text-xs">
                                                <p class="abilities-text mb-2">{{ $card['abilities'] }}</p>
                                                <div class="divider h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent my-1"></div>
                                                <p class="flavor-text mt-1 italic text-gray-700" style="font-family: 'Crimson Text', serif;">
                                                    {{ $card['flavor_text'] }}
                                                </p>
                                            </div>

                                            <!-- Footer -->
                                            <div class="card-footer flex justify-between items-center mt-1 bg-gradient-to-r from-gray-800 to-gray-700 p-1.5 rounded-md text-white text-xs shadow-lg">
                                                <span class="rarity-details font-medium tracking-wide">
                                                    <span class="@if($card['rarity'] == 'Mythic Rare') text-orange-400 @elseif($card['rarity'] == 'Rare') text-yellow-300 @endif">
                                                        {{ $card['rarity'] }}
                                                    </span>
                                                </span>
                                                @if(isset($card['power_toughness']))
                                                    <span class="power-toughness bg-gray-900 px-2 py-0.5 rounded-full font-bold shadow-inner">
                                                        {{ $card['power_toughness'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if(isset($cards))
                        <div class="mt-8">
                            {{ $cards->links() }}
                        </div>
                    @endif
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-purple-50 rounded-lg mb-6">
                                <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Cards Created</h3>
                            <p class="text-gray-600 mb-6 max-w-md mx-auto">Transform your AI images into unique cards by clicking the "Create Card" button on any image!</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="detailsModal" 
         class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
         role="dialog"
         aria-labelledby="modalTitle"
         aria-modal="true">
        <div class="bg-white rounded-lg p-6 max-w-3xl w-full mx-4 relative shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">Image Details</h2>
                <button onclick="closeModal()" 
                        class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-lg p-2"
                        aria-label="Close modal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="rounded-lg overflow-hidden">
                    <img id="modalImage" 
                         src="" 
                         alt="Selected image" 
                         class="w-full h-auto rounded-lg">
                </div>
                <div class="space-y-8">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-3">Prompt</h3>
                        <p id="modalPrompt" class="text-gray-900 text-lg leading-relaxed"></p>
                    </div>
                    <div class="space-y-4">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Settings</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 mb-1">Aspect Ratio</p>
                                <p id="modalAspectRatio" class="text-sm font-semibold text-gray-900"></p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 mb-1">Process Mode</p>
                                <p id="modalProcessMode" class="text-sm font-semibold text-gray-900"></p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg col-span-2">
                                <p class="text-xs font-medium text-gray-500 mb-1">Task ID</p>
                                <p id="modalTaskId" class="text-sm font-semibold text-gray-900 break-all"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Tab Styles */
        .tab-content {
            @apply mt-8;
        }
    </style>

    <!-- Include the 3D effect script -->
    <script src="{{ asset('js/mtg-card-3d-effect.js') }}"></script>
    
    <script>
        // Initialize 3D effects for cards
        function initializeCardEffects() {
            const cards = document.querySelectorAll('.mtg-card');
            cards.forEach(card => {
                if (card.closest('[data-rarity="Rare"]') || card.closest('[data-rarity="Mythic Rare"]')) {
                    new MTGCard3DTiltEffect(card);
                }
            });
        }

        function switchTab(tabName, updateHistory = true) {
            // Update button states
            document.querySelectorAll('[role="tab"]').forEach(tab => {
                const isActive = tab.getAttribute('data-tab') === tabName;
                tab.setAttribute('aria-selected', isActive);
                tab.classList.toggle('border-blue-500', isActive);
                tab.classList.toggle('text-blue-600', isActive);
                tab.classList.toggle('border-transparent', !isActive);
                tab.classList.toggle('text-gray-500', !isActive);
            });

            // Update content visibility
            const activeContent = document.getElementById(`${tabName}-tab`);
            const inactiveContents = Array.from(document.querySelectorAll('.tab-content')).filter(content => content !== activeContent);

            inactiveContents.forEach(content => {
                content.classList.add('hidden');
            });
            activeContent.classList.remove('hidden');
            
            // Reinitialize card effects when switching to cards tab
            if (tabName === 'cards') {
                initializeCardEffects();
            }

            // Update URL if needed
            if (updateHistory) {
                const url = new URL(window.location);
                url.searchParams.set('tab', tabName);
                window.history.pushState({tab: tabName}, '', url);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Initialize tab from URL parameter and setup card effects
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || 'images';
            switchTab(activeTab);
            
            // Initialize card effects after DOM is loaded
            initializeCardEffects();

            // Handle browser back/forward
            window.addEventListener('popstate', () => {
                const urlParams = new URLSearchParams(window.location.search);
                const activeTab = urlParams.get('tab') || 'images';
                switchTab(activeTab, false);
            });

            // Add keyboard navigation for gallery items
            const galleryItems = document.querySelectorAll('.image-container[role="button"]');
            galleryItems.forEach(item => {
                item.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        item.click();
                    }
                });
            });
        });

        function showDetails(prompt, imageUrl, aspectRatio, processMode, taskId) {
            const modal = document.getElementById('detailsModal');
            const modalImage = document.getElementById('modalImage');
            const modalPrompt = document.getElementById('modalPrompt');
            const modalAspectRatio = document.getElementById('modalAspectRatio');
            const modalProcessMode = document.getElementById('modalProcessMode');
            const modalTaskId = document.getElementById('modalTaskId');

            // Set modal content
            modalImage.src = imageUrl;
            modalPrompt.textContent = prompt;
            modalAspectRatio.textContent = aspectRatio;
            modalProcessMode.textContent = processMode;
            modalTaskId.textContent = taskId;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Trap focus within modal
            const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            const firstFocusableElement = focusableElements[0];
            const lastFocusableElement = focusableElements[focusableElements.length - 1];
            
            firstFocusableElement.focus();
            
            modal.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        if (document.activeElement === firstFocusableElement) {
                            e.preventDefault();
                            lastFocusableElement.focus();
                        }
                    } else {
                        if (document.activeElement === lastFocusableElement) {
                            e.preventDefault();
                            firstFocusableElement.focus();
                        }
                    }
                }
            });
        }

        function closeModal() {
            const modal = document.getElementById('detailsModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('detailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</x-app-layout>

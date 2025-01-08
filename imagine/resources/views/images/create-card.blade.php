<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Magic Card') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card Preview Container -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="mt-8">
                        <div id="card-container" class="mtg-card w-[375px] h-[525px] mx-auto relative rounded-[18px] shadow-lg overflow-hidden bg-white">
                            <div class="card-frame h-full p-3 flex flex-col">
                                <!-- Header: Card Name and Mana Cost -->
                                <div class="card-header flex justify-between items-center bg-gradient-to-r from-gray-200 to-gray-100 p-2 rounded-t-md mb-1">
                                    <h2 class="card-name text-xl font-bold text-shadow text-black">Unnamed Card</h2>
                                    <div class="mana-cost flex space-x-1">
                                        <div class="mana-symbol text-gray-500">No Mana Cost</div>
                                    </div>
                                </div>

                                <!-- Card Image -->
                                <img src="{{ $image->image_url }}" alt="Card Image" class="w-full h-[220px] object-cover object-center rounded mb-1">

                                <!-- Card Type -->
                                <div class="card-type bg-gradient-to-r from-gray-200 to-gray-100 p-2 text-sm border-b border-black border-opacity-20 mb-1 text-black">
                                    Unknown Type
                                </div>

                                <!-- Card Text: Abilities and Flavor Text -->
                                <div class="card-text bg-gray-100 bg-opacity-90 p-3 rounded flex-grow overflow-y-auto text-sm leading-relaxed text-black">
                                    <p class="abilities-text mb-2 text-black">No abilities</p>
                                    <p class="flavor-text mt-2 italic text-black">No flavor text</p>
                                </div>

                <!-- Footer: Rarity and Power/Toughness -->
                <div class="card-footer flex justify-between items-center text-white text-xs mt-1 bg-black bg-opacity-50 p-2 rounded-b-md">
                    <span class="rarity-details transition-all duration-500">???</span>
                    <span class="power-toughness">N/A</span>
                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Container -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    @if($cardExists)
                        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <p class="font-medium">Card Already Exists</p>
                            <p class="mt-1 text-sm">You have already created a card for this image. Each image can only be used for one card.</p>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('images.store-card') }}" class="space-y-6" {!! $cardExists ? 'onsubmit="return false;"' : '' !!}>
                    @csrf
                    <input type="hidden" name="image_url" value="{{ $image->image_url }}">
                    
                    <!-- Card Name -->
                    <div>
                        <x-input-label for="name" value="Card Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full {{ $cardExists ? 'opacity-50 cursor-not-allowed' : '' }}" required {{ $cardExists ? 'disabled' : '' }} />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Mana Cost -->
                    <div>
                        <x-input-label for="mana_cost" value="Mana Cost (e.g., 2RG for 2 colorless, 1 red, 1 green)" />
                        <x-text-input id="mana_cost" name="mana_cost" type="text" class="mt-1 block w-full {{ $cardExists ? 'opacity-50 cursor-not-allowed' : '' }}" required {{ $cardExists ? 'disabled' : '' }} />
                        <x-input-error :messages="$errors->get('mana_cost')" class="mt-2" />
                    </div>

                    <!-- Card Type -->
                    <div>
                        <x-input-label for="card_type" value="Card Type" />
                        <x-text-input id="card_type" name="card_type" type="text" class="mt-1 block w-full {{ $cardExists ? 'opacity-50 cursor-not-allowed' : '' }}" required placeholder="e.g., Creature - Dragon" {{ $cardExists ? 'disabled' : '' }} />
                        <x-input-error :messages="$errors->get('card_type')" class="mt-2" />
                    </div>

                    <!-- Card Text/Abilities -->
                    <div>
                        <x-input-label for="abilities" value="Card Text/Abilities" />
                        <textarea id="abilities" name="abilities" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{ $cardExists ? 'opacity-50 cursor-not-allowed' : '' }}" required {{ $cardExists ? 'disabled' : '' }}></textarea>
                        <x-input-error :messages="$errors->get('abilities')" class="mt-2" />
                    </div>

                    <!-- Flavor Text -->
                    <div>
                        <x-input-label for="flavor_text" value="Flavor Text (optional)" />
                        <textarea id="flavor_text" name="flavor_text" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{ $cardExists ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $cardExists ? 'disabled' : '' }}></textarea>
                        <x-input-error :messages="$errors->get('flavor_text')" class="mt-2" />
                    </div>

                    <!-- Power/Toughness (for creatures) -->
                    <div>
                        <x-input-label for="power_toughness" value="Power/Toughness (for creatures, e.g. 3/4)" />
                        <x-text-input id="power_toughness" name="power_toughness" type="text" class="mt-1 block w-full {{ $cardExists ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $cardExists ? 'disabled' : '' }} />
                        <x-input-error :messages="$errors->get('power_toughness')" class="mt-2" />
                    </div>

                    <!-- Rarity Info -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Card rarity will be randomly assigned upon creation with the following probabilities:</p>
                        <ul class="mt-2 text-sm text-gray-600 list-disc list-inside">
                            <li>Common: 50%</li>
                            <li>Uncommon: 30%</li>
                            <li>Rare: 15%</li>
                            <li>Mythic Rare: 5%</li>
                        </ul>
                    </div>

                    <div class="flex justify-end mt-6">
                        @if($cardExists)
                            <x-primary-button disabled class="opacity-50 cursor-not-allowed bg-gray-400">
                                {{ __('Card Already Created') }}
                            </x-primary-button>
                        @else
                            <x-primary-button class="!text-white !bg-gray-800 hover:!bg-gray-700">
                                {{ __('Create Card') }}
                            </x-primary-button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes flipInY {
            from {
                transform: perspective(400px) rotateY(90deg);
                animation-timing-function: ease-in;
                opacity: 0;
            }
            40% {
                transform: perspective(400px) rotateY(-20deg);
                animation-timing-function: ease-in;
            }
            60% {
                transform: perspective(400px) rotateY(10deg);
                opacity: 1;
            }
            80% {
                transform: perspective(400px) rotateY(-5deg);
            }
            to {
                transform: perspective(400px);
            }
        }

        @keyframes glowPulse {
            0% { box-shadow: 0 0 5px var(--glow-color); }
            50% { box-shadow: 0 0 20px var(--glow-color); }
            100% { box-shadow: 0 0 5px var(--glow-color); }
        }

        .rarity-reveal {
            animation: flipInY 1s ease-out;
        }

        .rarity-common {
            --glow-color: rgba(255, 255, 255, 0.5);
        }

        .rarity-uncommon {
            --glow-color: rgba(192, 192, 192, 0.7);
            color: #c0c0c0 !important;
        }

        .rarity-rare {
            --glow-color: rgba(255, 215, 0, 0.7);
            color: #ffd700 !important;
        }

        .rarity-mythic {
            --glow-color: rgba(255, 69, 0, 0.7);
            color: #ff4500 !important;
        }

        .glow-effect {
            animation: glowPulse 2s infinite;
        }

        .card-container-reveal {
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }
    </style>

    <script>
        // Form submission and animations
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form');
            const cardContainer = document.getElementById('card-container');
            
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="inline-flex items-center"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Creating...</span>';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: new FormData(form)
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        // Trigger rarity reveal animation
                        cardContainer.classList.add('card-container-reveal');
                        
                        // Add flip animation
                        setTimeout(() => {
                            const raritySpan = document.querySelector('.rarity-details');
                            raritySpan.textContent = data.card.rarity;
                            raritySpan.classList.add('rarity-reveal');
                            
                            // Add rarity-specific styling
                            const rarityClass = `rarity-${data.card.rarity.toLowerCase().replace(' ', '-')}`;
                            cardContainer.classList.add(rarityClass, 'glow-effect');
                            
                            // Redirect after animation
                            setTimeout(() => {
                                window.location.href = '{{ route('images.gallery') }}';
                            }, 2000);
                        }, 500);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Create Card';
                    
                    // Show error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded';
                    errorDiv.textContent = 'An error occurred while creating the card. Please try again.';
                    form.insertBefore(errorDiv, submitButton.parentElement);
                }
            });

            // Check for existing card with same image URL
            const imageUrl = '{{ $image->image_url }}';
            const cards = JSON.parse(sessionStorage.getItem('cards') || '[]');
            const existingCard = cards.find(card => card.image_url === imageUrl);
            
            if (existingCard) {
                const form = document.querySelector('form');
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                
                const errorDiv = document.createElement('div');
                errorDiv.className = 'mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded';
                errorDiv.textContent = 'You have already created a card for this image.';
                form.insertBefore(errorDiv, submitButton.parentElement);
            }

            // Store cards in session storage for validation
            const response = await fetch('{{ route("images.gallery") }}');
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const cards = Array.from(doc.querySelectorAll('[data-card-container]')).map(el => ({
                image_url: el.getAttribute('data-image-url')
            }));
            sessionStorage.setItem('cards', JSON.stringify(cards));

            // Add error handling for validation response
            if (!response.ok) {
                const errorData = await response.json();
                if (errorData.errors && errorData.errors.image_url) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded';
                    errorDiv.textContent = errorData.errors.image_url[0];
                    form.insertBefore(errorDiv, submitButton.parentElement);
                }
                submitButton.disabled = false;
                submitButton.innerHTML = 'Create Card';
                return;
            }
        });

        // Live preview updates
        document.addEventListener('DOMContentLoaded', () => {
            const updatePreview = () => {
                const name = document.getElementById('name').value || 'Unnamed Card';
                const manaCost = document.getElementById('mana_cost').value;
                const cardType = document.getElementById('card_type').value || 'Unknown Type';
                const abilities = document.getElementById('abilities').value || 'No abilities';
                const flavorText = document.getElementById('flavor_text').value;
                const powerToughness = document.getElementById('power_toughness').value || 'N/A';
                // Update card name with preserved class
                const cardNameEl = document.querySelector('.card-name');
                cardNameEl.textContent = name;
                cardNameEl.className = 'card-name text-xl font-bold text-shadow text-black';

                // Update mana cost
                const manaContainer = document.querySelector('.mana-cost');
                if (manaCost) {
                    manaContainer.innerHTML = manaCost.split('').map(symbol => {
                        const bgColor = {
                            'W': 'bg-yellow-200 text-black',
                            'U': 'bg-blue-500 text-white',
                            'B': 'bg-black text-white',
                            'R': 'bg-red-500 text-white',
                            'G': 'bg-green-500 text-white'
                        }[symbol.toUpperCase()] || 'bg-gray-400 text-black';
                        
                        return `<div class="mana-symbol rounded-full flex justify-center items-center text-sm font-bold w-8 h-8 ${bgColor}">${symbol}</div>`;
                    }).join('');
                }

                // Update card type with preserved class
                const cardTypeEl = document.querySelector('.card-type');
                cardTypeEl.textContent = cardType;
                cardTypeEl.className = 'card-type bg-gradient-to-r from-gray-200 to-gray-100 p-2 text-sm border-b border-black border-opacity-20 mb-1 text-black';

                // Update abilities and flavor text with preserved classes
                const abilitiesEl = document.querySelector('.abilities-text');
                abilitiesEl.textContent = abilities;
                abilitiesEl.className = 'abilities-text mb-2 text-black';

                const flavorTextEl = document.querySelector('.flavor-text');
                flavorTextEl.textContent = flavorText || 'No flavor text';
                flavorTextEl.className = 'flavor-text mt-2 italic text-black';

                // Update footer (only power/toughness since rarity is random)
                document.querySelector('.rarity-details').textContent = '???';
                document.querySelector('.power-toughness').textContent = powerToughness;
            };

            // Add event listeners to all form fields
            const formFields = document.querySelectorAll('input, textarea, select');
            formFields.forEach(field => {
                field.addEventListener('input', updatePreview);
            });
        });
    </script>
</x-app-layout>

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
                                    <span class="rarity-details">Common</span>
                                    <span class="power-toughness">N/A</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Container -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form method="POST" action="{{ route('images.store-card') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="image_url" value="{{ $image->image_url }}">
                    
                    <!-- Card Name -->
                    <div>
                        <x-input-label for="name" value="Card Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Mana Cost -->
                    <div>
                        <x-input-label for="mana_cost" value="Mana Cost (e.g., 2RG for 2 colorless, 1 red, 1 green)" />
                        <x-text-input id="mana_cost" name="mana_cost" type="text" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('mana_cost')" class="mt-2" />
                    </div>

                    <!-- Card Type -->
                    <div>
                        <x-input-label for="card_type" value="Card Type" />
                        <x-text-input id="card_type" name="card_type" type="text" class="mt-1 block w-full" required placeholder="e.g., Creature - Dragon" />
                        <x-input-error :messages="$errors->get('card_type')" class="mt-2" />
                    </div>

                    <!-- Card Text/Abilities -->
                    <div>
                        <x-input-label for="abilities" value="Card Text/Abilities" />
                        <textarea id="abilities" name="abilities" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
                        <x-input-error :messages="$errors->get('abilities')" class="mt-2" />
                    </div>

                    <!-- Flavor Text -->
                    <div>
                        <x-input-label for="flavor_text" value="Flavor Text (optional)" />
                        <textarea id="flavor_text" name="flavor_text" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                        <x-input-error :messages="$errors->get('flavor_text')" class="mt-2" />
                    </div>

                    <!-- Power/Toughness (for creatures) -->
                    <div>
                        <x-input-label for="power_toughness" value="Power/Toughness (for creatures, e.g. 3/4)" />
                        <x-text-input id="power_toughness" name="power_toughness" type="text" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('power_toughness')" class="mt-2" />
                    </div>

                    <!-- Rarity -->
                    <div>
                        <x-input-label for="rarity" value="Rarity" />
                        <select id="rarity" name="rarity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="Common">Common</option>
                            <option value="Uncommon">Uncommon</option>
                            <option value="Rare">Rare</option>
                            <option value="Mythic Rare">Mythic Rare</option>
                        </select>
                        <x-input-error :messages="$errors->get('rarity')" class="mt-2" />
                    </div>

                    <div class="flex justify-end mt-6 [&>*]:!text-white [&>*]:!bg-gray-800 [&>*]:hover:!bg-gray-700">
                        <x-primary-button>
                            {{ __('Create Card') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Live preview updates
        document.addEventListener('DOMContentLoaded', () => {
            const updatePreview = () => {
                const name = document.getElementById('name').value || 'Unnamed Card';
                const manaCost = document.getElementById('mana_cost').value;
                const cardType = document.getElementById('card_type').value || 'Unknown Type';
                const abilities = document.getElementById('abilities').value || 'No abilities';
                const flavorText = document.getElementById('flavor_text').value;
                const powerToughness = document.getElementById('power_toughness').value || 'N/A';
                const rarity = document.getElementById('rarity').value || 'Common';

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

                // Update footer
                document.querySelector('.rarity-details').textContent = rarity;
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

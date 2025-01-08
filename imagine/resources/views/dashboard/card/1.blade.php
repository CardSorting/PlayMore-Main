{% extends "base.html" %}

{% block content %}
<div class="flex justify-center items-center min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 p-4">
    <!-- Enhanced Card Layout with Perspective Effect -->
    <div id="card-container" class="mtg-card w-[90vw] sm:w-[375px] h-[80vh] sm:h-[525px] relative text-black rounded-[20px] shadow-2xl overflow-hidden transition-all duration-500 hover:scale-105 hover:shadow-[0_0_30px_rgba(255,215,0,0.3)]" style="transform-style: preserve-3d; perspective: 1000px;">
        <!-- Holographic Overlay -->
        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 pointer-events-none z-10"></div>
        
        <div class="card-frame h-full p-4 flex flex-col bg-gradient-to-b from-[#f8f8f8] to-[#e8e8e8] border border-gray-300">
            <!-- Enhanced Header -->
            <div class="card-header flex justify-between items-center bg-gradient-to-r from-[#e9e5cd] to-[#f5f1e6] p-3 rounded-lg mb-2 shadow-sm border border-gray-200">
                <h2 class="card-name text-xl font-bold" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                    {{ card.name or 'Unnamed Card' }}
                </h2>
                <div class="mana-cost flex space-x-1.5">
                    {% if card.mana_cost %}
                        {% for symbol in card.mana_cost %}
                            <div class="mana-symbol rounded-full flex justify-center items-center text-sm font-bold w-7 h-7 shadow-lg transform hover:scale-110 transition-transform duration-200
                                {% if symbol|lower == 'w' %}bg-gradient-to-br from-yellow-100 to-yellow-300 text-black border-2 border-yellow-400
                                {% elif symbol|lower == 'u' %}bg-gradient-to-br from-blue-400 to-blue-600 text-white border-2 border-blue-300
                                {% elif symbol|lower == 'b' %}bg-gradient-to-br from-gray-800 to-black text-white border-2 border-gray-600
                                {% elif symbol|lower == 'r' %}bg-gradient-to-br from-red-400 to-red-600 text-white border-2 border-red-300
                                {% elif symbol|lower == 'g' %}bg-gradient-to-br from-green-400 to-green-600 text-white border-2 border-green-300
                                {% else %}bg-gradient-to-br from-gray-300 to-gray-500 text-black border-2 border-gray-400{% endif %}">
                                {{ symbol }}
                            </div>
                        {% endfor %}
                    {% else %}
                        <div class="mana-symbol text-gray-500 italic text-sm">No Mana Cost</div>
                    {% endif %}
                </div>
            </div>

            <!-- Enhanced Card Image Container -->
            <div class="relative rounded-lg overflow-hidden mb-2 shadow-lg">
                <img src="{{ card.image_url or '/static/images/placeholder.png' }}" 
                     alt="{{ card.name or 'Unknown Card' }}" 
                     class="w-full h-[220px] object-cover object-center transform hover:scale-105 transition-transform duration-500" 
                     loading="lazy" 
                     onerror="this.src='/static/card_images/default_card.png'">
                <!-- Image Overlay Effect -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
            </div>

            <!-- Enhanced Card Type -->
            <div class="card-type bg-gradient-to-r from-[#e9e5cd] to-[#f5f1e6] p-2.5 text-sm border border-gray-200 rounded-md mb-2 font-semibold shadow-sm">
                {{ card.card_type or 'Unknown Type' }}
            </div>

            <!-- Enhanced Card Text Box -->
            <div class="card-text bg-[#f5f1e6] rounded-lg flex-grow overflow-y-auto p-4 shadow-inner border border-gray-200">
                <p class="abilities-text mb-3 text-sm leading-relaxed">{{ card.abilities or 'No abilities' }}</p>
                <div class="divider h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent my-2"></div>
                <p class="flavor-text mt-2 italic text-sm text-gray-700" style="font-family: 'Crimson Text', serif;">
                    {{ card.flavor_text or 'No flavor text' }}
                </p>
            </div>

            <!-- Enhanced Footer -->
            <div class="card-footer flex justify-between items-center mt-2 bg-gradient-to-r from-gray-800 to-gray-700 p-2.5 rounded-md text-white shadow-lg">
                <span class="rarity-details text-xs font-medium tracking-wide">
                    <span class="{% if card.rarity == 'Mythic Rare' %}text-orange-400{% elif card.rarity == 'Rare' %}text-yellow-300{% endif %}">
                        {{ card.rarity or 'Common' }}
                    </span>
                    <span class="text-gray-300">({{ card.set_name }}-{{ card.card_number }})</span>
                </span>
                <span class="power-toughness bg-gray-900 px-3 py-1 rounded-full text-sm font-bold shadow-inner">
                    {{ card.power_toughness or 'N/A' }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Include the 3D effect script -->
<script src="{{ asset('js/mtg-card-3d-effect.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cardElement = document.getElementById('card-container');
    if (cardElement) {
        const cardRarity = '{{ card.rarity }}';
        if (cardRarity === 'Rare' || cardRarity === 'Mythic Rare') {
            new MTGCard3DTiltEffect(cardElement);
        }
    }
});
</script>

<style>
/* Custom Scrollbar for Card Text */
.card-text::-webkit-scrollbar {
    width: 6px;
}

.card-text::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 3px;
}

.card-text::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 3px;
}

.card-text::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.3);
}

/* Smooth Font Rendering */
.card-name, .card-type, .abilities-text {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Enhanced Card Frame Border Effect */
.card-frame {
    box-shadow: 
        inset 0 0 10px rgba(0,0,0,0.1),
        0 0 20px rgba(0,0,0,0.2);
}

/* Mana Symbol Hover Animation */
.mana-symbol {
    transition: all 0.3s ease;
}

.mana-symbol:hover {
    transform: translateY(-2px) scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
</style>
{% endblock %}

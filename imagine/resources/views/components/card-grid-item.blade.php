<div class="card-item opacity-0 transform transition-all duration-500 w-1/3 px-3 mb-6"
     data-rarity="{{ $getDataAttributes()['rarity'] }}"
     data-type="{{ $getDataAttributes()['type'] }}"
     data-name="{{ $getDataAttributes()['name'] }}"
     x-on:mouseenter="hoveredCard = $el"
     x-on:mouseleave="hoveredCard = null"
     :class="{ 'z-20': hoveredCard === $el }"
     style="animation: fadeInScale 0.6s ease-out forwards;">
    <div class="mtg-card bg-white overflow-hidden shadow-xl rounded-xl p-3 relative aspect-[2.5/3.5]
                hover:shadow-[0_10px_40px_-15px_rgba(0,0,0,0.3)]
                transition-shadow duration-300">
        <livewire:card-display :card="$card" :wire:key="'card-'.$card['name']" />
    </div>
</div>

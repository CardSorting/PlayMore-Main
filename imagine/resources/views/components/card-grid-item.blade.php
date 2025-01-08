<div class="card-item transform transition-all duration-300 hover:translate-y-[-4px]"
     data-rarity="{{ $getDataAttributes()['rarity'] }}"
     data-type="{{ $getDataAttributes()['type'] }}"
     data-name="{{ $getDataAttributes()['name'] }}"
     x-on:mouseenter="hoveredCard = $el"
     x-on:mouseleave="hoveredCard = null"
     :class="{ 'z-10': hoveredCard === $el, 'scale-105': hoveredCard === $el }">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 relative">
        <livewire:card-display :card="$card" :wire:key="'card-'.$card['name']" />
    </div>
</div>

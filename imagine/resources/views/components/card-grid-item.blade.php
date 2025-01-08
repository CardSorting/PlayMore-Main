<div class="card-item opacity-0 transform transition-all duration-500 w-1/3 px-3 mb-6"
     x-on:mouseenter="hoveredCard = $el"
     x-on:mouseleave="hoveredCard = null"
     :class="{ 'z-20': hoveredCard === $el }"
     style="animation: fadeInScale 0.6s ease-out forwards;">
    <div class="mtg-card bg-white overflow-hidden rounded-xl p-3 relative aspect-[2.5/3.5] shadow-[0_4px_12px_-2px_rgba(0,0,0,0.12),0_0_0_1px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_50px_-12px_rgba(0,0,0,0.25),0_0_0_1px_rgba(0,0,0,0.1)] hover:translate-y-[-4px] transition-all duration-300 ease-out"
         data-rarity="{{ $getDataAttributes()['rarity'] }}"
         data-type="{{ $getDataAttributes()['type'] }}"
         data-name="{{ $getDataAttributes()['name'] }}">
        <livewire:card-display :card="$card" :wire:key="'card-'.$card['name']" />
    </div>
</div>

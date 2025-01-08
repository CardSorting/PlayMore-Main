<div class="card-item opacity-0 transform transition-all duration-500 w-1/3 px-3 mb-6"
     x-on:mouseenter="hoveredCard = $el"
     x-on:mouseleave="hoveredCard = null"
     :class="{ 'z-20': hoveredCard === $el }"
     style="animation: fadeInScale 0.6s ease-out forwards;">
    <div class="card-container bg-white overflow-hidden rounded-xl p-3 relative aspect-[2.5/3.5] transition-all duration-300 ease-out"
         data-card-container
         data-image-url="{{ $card['image_url'] }}"
         data-rarity="{{ $getDataAttributes()['rarity'] }}"
         data-type="{{ $getDataAttributes()['type'] }}"
         data-name="{{ $getDataAttributes()['name'] }}">
        <livewire:card-display :card="$card" :wire:key="'card-'.$card['name']" />
    </div>
</div>

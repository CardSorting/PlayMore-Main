<div class="card-item transform transition-all duration-500 h-full w-full"
     x-on:mouseenter="hoveredCard = $el"
     x-on:mouseleave="hoveredCard = null"
     :class="{ 'z-20': hoveredCard === $el }">
    <div class="card-container bg-white overflow-hidden rounded-xl p-4 relative transition-all duration-300 ease-out w-full h-full"
         style="aspect-ratio: 2.5/3.5; min-height: 400px;"
         data-card-container
         data-image-url="{{ $card['image_url'] }}"
         data-rarity="{{ $getDataAttributes()['rarity'] }}"
         data-type="{{ $getDataAttributes()['type'] }}"
         data-name="{{ $getDataAttributes()['name'] }}">
        <livewire:card-display :card="$card" :wire:key="'card-'.$card['name']" />
    </div>
</div>

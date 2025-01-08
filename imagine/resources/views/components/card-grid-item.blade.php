<div class="card-item transform transition-all duration-500 h-full"
     x-on:mouseenter="hoveredCard = $el"
     x-on:mouseleave="hoveredCard = null"
     :class="{ 'z-20': hoveredCard === $el }">
    <div class="card-container bg-white overflow-hidden rounded-xl p-4 relative aspect-[2.5/3.5] transition-all duration-300 ease-out h-full"
         data-card-container
         data-image-url="{{ $card['image_url'] }}"
         data-rarity="{{ $getDataAttributes()['rarity'] }}"
         data-type="{{ $getDataAttributes()['type'] }}"
         data-name="{{ $getDataAttributes()['name'] }}">
        <livewire:card-display :card="$card" :wire:key="'card-'.$card['name']" />
    </div>
</div>

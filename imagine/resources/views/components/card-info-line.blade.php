<!-- Info Line Background Effects -->
<div class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent"></div>
<div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgba(255,255,255,0.1),transparent_70%)] mix-blend-overlay"></div>

<!-- Info Line Border Effects -->
<div class="absolute bottom-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
<div class="absolute inset-[1px] border-t border-white/5"></div>

<!-- Info Line Side Ornaments -->
<div class="absolute left-0 top-0 bottom-0 w-6">
    <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-r from-white/10 to-transparent"></div>
    <div class="absolute inset-y-1 left-2 w-3 border-t border-b border-white/5"></div>
</div>

<div class="absolute right-0 top-0 bottom-0 w-6">
    <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-l from-white/10 to-transparent"></div>
    <div class="absolute inset-y-1 right-2 w-3 border-t border-b border-white/5"></div>
</div>

<!-- Info Line Inner Shadow -->
<div class="absolute inset-0 shadow-inner opacity-20"></div>

<!-- Info Line Texture -->
<div class="absolute inset-0 opacity-5 mix-blend-overlay" 
     style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSJub25lIiBzdHJva2U9IiNmZmYiIHN0cm9rZS1vcGFjaXR5PSIwLjEiIHN0cm9rZS13aWR0aD0iMC41Ii8+Cjwvc3ZnPg==');">
</div>

@props(['rarity', 'powerToughness' => null])

<!-- Info Line Content Container -->
<div class="relative flex justify-between items-center w-full z-10">
    <!-- Rarity Info -->
    <div class="flex items-center space-x-2">
        <span class="rarity-symbol text-xs
            @if(strtolower($rarity) === 'mythic rare') text-orange-400
            @elseif(strtolower($rarity) === 'rare') text-yellow-300
            @elseif(strtolower($rarity) === 'uncommon') text-gray-400
            @else text-gray-600 @endif">
            @if(strtolower($rarity) === 'mythic rare') M
            @elseif(strtolower($rarity) === 'rare') R
            @elseif(strtolower($rarity) === 'uncommon') U
            @else C @endif
        </span>
        <span class="rarity-details font-medium tracking-wide">{{ $rarity }}</span>
    </div>

    <!-- Power/Toughness -->
    @if($powerToughness)
        <span class="power-toughness font-bold">{{ $powerToughness }}</span>
    @endif
</div>

<!-- Info Line Highlight -->
<div class="absolute inset-x-0 top-0 h-px bg-white/10"></div>

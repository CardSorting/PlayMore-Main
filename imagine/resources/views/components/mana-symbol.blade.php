@props(['symbol'])

<div class="mana-symbol rounded-full flex justify-center items-center text-xs font-bold w-6 h-6 transform transition-transform duration-200 relative
    @if(strtolower($symbol) == 'w') bg-gradient-to-br from-white to-[#e6e6e6] text-[#211d15] shadow-inner
    @elseif(strtolower($symbol) == 'u') bg-gradient-to-br from-[#0e67ab] to-[#064e87] text-white shadow-inner
    @elseif(strtolower($symbol) == 'b') bg-gradient-to-br from-[#2b2824] to-[#171512] text-[#d3d4d5] shadow-inner
    @elseif(strtolower($symbol) == 'r') bg-gradient-to-br from-[#d3202a] to-[#aa1017] text-[#f9e6e7] shadow-inner
    @elseif(strtolower($symbol) == 'g') bg-gradient-to-br from-[#00733e] to-[#005c32] text-[#c4d3ca] shadow-inner
    @else bg-gradient-to-br from-[#beb9b2] to-[#a7a29c] text-[#171512] shadow-inner
    @endif
    group-hover:scale-110">
    
    <!-- Mana Symbol Inner Glow -->
    <div class="absolute inset-0 rounded-full opacity-50
        @if(strtolower($symbol) == 'w') bg-[radial-gradient(circle_at_50%_0%,rgba(255,255,255,0.5),transparent_70%)]
        @elseif(strtolower($symbol) == 'u') bg-[radial-gradient(circle_at_50%_0%,rgba(14,103,171,0.5),transparent_70%)]
        @elseif(strtolower($symbol) == 'b') bg-[radial-gradient(circle_at_50%_0%,rgba(43,40,36,0.5),transparent_70%)]
        @elseif(strtolower($symbol) == 'r') bg-[radial-gradient(circle_at_50%_0%,rgba(211,32,42,0.5),transparent_70%)]
        @elseif(strtolower($symbol) == 'g') bg-[radial-gradient(circle_at_50%_0%,rgba(0,115,62,0.5),transparent_70%)]
        @else bg-[radial-gradient(circle_at_50%_0%,rgba(190,185,178,0.5),transparent_70%)]
        @endif">
    </div>

    <!-- Mana Symbol Texture -->
    <div class="absolute inset-0 rounded-full bg-white opacity-20 mix-blend-overlay"></div>

    <!-- Mana Symbol Text -->
    <span class="relative z-10">{{ strtoupper($symbol) }}</span>
</div>

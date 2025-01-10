<!-- Type Line Background -->
<div class="absolute inset-0 bg-[#171314]"></div>

<!-- Type Line Gradients -->
<div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent mix-blend-overlay"></div>
<div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(255,255,255,0.1),transparent_70%)] mix-blend-overlay"></div>

<!-- Type Line Border Effects -->
<div class="absolute inset-0 border-t-2 border-b-2 border-[#171314]"></div>
<div class="absolute inset-[1px] border-t border-b border-white/10"></div>

<!-- Type Line Side Ornaments -->
<div class="absolute left-0 top-0 bottom-0 w-8">
    <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-r from-[#171314] to-transparent opacity-20"></div>
    <div class="absolute inset-y-1 left-2 w-4 border-t border-b border-[#171314]/20"></div>
</div>

<div class="absolute right-0 top-0 bottom-0 w-8">
    <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-l from-[#171314] to-transparent opacity-20"></div>
    <div class="absolute inset-y-1 right-2 w-4 border-t border-b border-[#171314]/20"></div>
</div>

<!-- Type Line Inner Shadow -->
<div class="absolute inset-0 shadow-inner opacity-30"></div>

<!-- Type Line Edge Highlights -->
<div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
<div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-black/20 to-transparent"></div>

<!-- Type Line Texture -->
<div class="absolute inset-0 opacity-5 mix-blend-overlay" 
     style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSJub25lIiBzdHJva2U9IiNmZmYiIHN0cm9rZS1vcGFjaXR5PSIwLjEiIHN0cm9rZS13aWR0aD0iMC41Ii8+Cjwvc3ZnPg==');">
</div>

<!-- Type Line Content Container -->
<div class="relative px-4 py-1.5 text-sm font-matrix bg-[#f8e7c9] text-[#171314] tracking-wide">
    {{ $slot }}
</div>

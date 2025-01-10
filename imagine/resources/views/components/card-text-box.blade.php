<!-- Text Box Background Effects -->
<div class="absolute inset-0 bg-gradient-to-br from-white/20 via-transparent to-black/5"></div>
<div class="absolute inset-[1px] border border-[#171314]/10 rounded-sm"></div>
<div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgba(255,255,255,0.2),transparent_70%)] mix-blend-overlay"></div>

<!-- Text Box Corner Ornaments -->
<div class="absolute top-0 left-0 w-2 h-2 border-t border-l border-[#171314]/20
            before:absolute before:inset-0 before:border-t before:border-l before:border-white/10
            after:absolute after:top-0.5 after:left-0.5 after:w-1 after:h-1 after:border-t after:border-l after:border-[#171314]/10"></div>

<div class="absolute top-0 right-0 w-2 h-2 border-t border-r border-[#171314]/20
            before:absolute before:inset-0 before:border-t before:border-r before:border-white/10
            after:absolute after:top-0.5 after:right-0.5 after:w-1 after:h-1 after:border-t after:border-r after:border-[#171314]/10"></div>

<div class="absolute bottom-0 left-0 w-2 h-2 border-b border-l border-[#171314]/20
            before:absolute before:inset-0 before:border-b before:border-l before:border-white/10
            after:absolute after:bottom-0.5 after:left-0.5 after:w-1 after:h-1 after:border-b after:border-l after:border-[#171314]/10"></div>

<div class="absolute bottom-0 right-0 w-2 h-2 border-b border-r border-[#171314]/20
            before:absolute before:inset-0 before:border-b before:border-r before:border-white/10
            after:absolute after:bottom-0.5 after:right-0.5 after:w-1 after:h-1 after:border-b after:border-r after:border-[#171314]/10"></div>

<!-- Text Box Edge Highlights -->
<div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
<div class="absolute inset-y-0 left-0 w-px bg-gradient-to-b from-transparent via-white/20 to-transparent"></div>
<div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-black/20 to-transparent"></div>
<div class="absolute inset-y-0 right-0 w-px bg-gradient-to-b from-transparent via-black/20 to-transparent"></div>

<!-- Text Box Inner Shadow -->
<div class="absolute inset-0 shadow-inner opacity-10"></div>

<!-- Text Box Texture -->
<div class="absolute inset-0 opacity-5 mix-blend-overlay" 
     style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSJub25lIiBzdHJva2U9IiMxNzEzMTQiIHN0cm9rZS1vcGFjaXR5PSIwLjEiIHN0cm9rZS13aWR0aD0iMC41Ii8+Cjwvc3ZnPg==');">
</div>

<!-- Text Box Content Container -->
<div class="relative p-4">
    {{ $slot }}
</div>

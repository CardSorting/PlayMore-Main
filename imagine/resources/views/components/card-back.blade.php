@props(['card'])

<div class="card-back h-full bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-900 p-0.5">
    <div class="h-full bg-gradient-to-br from-indigo-800 via-purple-800 to-indigo-800 rounded-lg p-4">
        <!-- Decorative Pattern -->
        <div class="relative h-full border-4 border-gold-500 rounded-lg overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10" 
                 style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+CjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB4PSIwIiB5PSIwIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiPgogIDxwYXRoIGQ9Ik0gMjAgMjAgTCAwIDQwIE0gNDAgMCBMIDAgNDAgTSA0MCAyMCBMIDIwIDQwIE0gMjAgMCBMIDQwIDIwIE0gMCAwIEwgNDAgNDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0iI2ZmZDcwMCIgc3Ryb2tlLXdpZHRoPSIxIi8+CjwvcGF0dGVybj4KPHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPgo8L3N2Zz4=');">
            </div>

            <!-- Radial Gradients -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(255,215,0,0.1),transparent)]"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-32 h-32 bg-gradient-to-r from-gold-400 via-gold-200 to-gold-400 rounded-full opacity-20 animate-pulse"></div>
            </div>

            <!-- Card Info -->
            <div class="relative h-full flex flex-col items-center justify-center text-center p-6 text-gold-200">
                <h3 class="text-xl font-bold mb-4 font-matrix">{{ $card['name'] }}</h3>
                <p class="text-sm mb-4 font-matrix">{{ $card['card_type'] }}</p>
                <p class="text-xs italic font-matrix">{{ $card['rarity'] }}</p>

                <!-- Corner Ornaments -->
                <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-gold-500/50"></div>
                <div class="absolute top-0 right-0 w-8 h-8 border-t-2 border-r-2 border-gold-500/50"></div>
                <div class="absolute bottom-0 left-0 w-8 h-8 border-b-2 border-l-2 border-gold-500/50"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-gold-500/50"></div>

                <!-- Inner Glow -->
                <div class="absolute inset-4 rounded-lg opacity-20"
                     style="background: radial-gradient(circle at center, rgba(255,215,0,0.3) 0%, transparent 70%);"></div>
            </div>

            <!-- Edge Highlights -->
            <div class="absolute inset-0 rounded-lg">
                <div class="absolute inset-0 bg-gradient-to-t from-transparent via-white/5 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-l from-transparent via-white/5 to-transparent"></div>
            </div>
        </div>
    </div>
</div>

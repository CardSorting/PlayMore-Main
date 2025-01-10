@props(['sizes'])

<div x-data="{ 
    selectedSize: '{{ old('size', '') }}',
    sizes: @js($sizes),
    get price() {
        if (!this.selectedSize) return 0;
        return this.sizes[this.selectedSize].price / 100;
    },
    get formattedPrice() {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(this.price);
    }
}" class="sticky top-6 mt-6 space-y-6">
    <!-- Order Summary Card -->
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-6">
            <!-- Price Display -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <h2 class="text-base font-medium text-gray-900">Base Price</h2>
                <div class="text-xl font-medium text-gray-900" x-show="selectedSize" x-text="formattedPrice"></div>
                <div class="text-sm text-gray-500 italic" x-show="!selectedSize">Select a size</div>
            </div>

            <!-- Action Button -->
            <div class="mt-6">
                <button type="submit"
                        x-bind:disabled="!selectedSize"
                        x-bind:class="{'opacity-50 cursor-not-allowed': !selectedSize}"
                        class="w-full rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Continue to Material Selection
                </button>
            </div>
        </div>

        <!-- Trust Badges -->
        <div class="border-t border-gray-200 px-6 py-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <p class="ml-2 text-xs text-gray-500">Secure checkout</p>
                </div>
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <p class="ml-2 text-xs text-gray-500">Free shipping</p>
                </div>
            </div>
        </div>
    </div>
</div>

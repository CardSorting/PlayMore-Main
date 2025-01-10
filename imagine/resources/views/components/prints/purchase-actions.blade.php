@props(['sizes'])

<div x-data="{ 
    selectedSize: '{{ old('size', '') }}',
    quantity: 1,
    sizes: @js($sizes),
    get price() {
        return this.selectedSize ? this.sizes[this.selectedSize].price / 100 : 0;
    },
    get total() {
        return (this.price * this.quantity).toFixed(2);
    },
    get formattedPrice() {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(this.price);
    },
    get formattedTotal() {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(this.total);
    }
}" class="mt-6 space-y-6">
    <!-- Price Display -->
    <div class="flex items-center justify-between">
        <h2 class="text-sm font-medium text-gray-900">Price</h2>
        <div class="text-xl font-medium text-gray-900" x-show="selectedSize" x-text="formattedPrice"></div>
        <div class="text-sm text-gray-500 italic" x-show="!selectedSize">Select a size to see price</div>
    </div>

    <!-- Quantity Selector -->
    <div>
        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
        <div class="mt-2 flex rounded-md shadow-sm">
            <button type="button" 
                    @click="quantity = Math.max(1, quantity - 1)"
                    class="relative inline-flex items-center rounded-l-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                -
            </button>
            <div class="relative flex flex-1 items-center justify-center bg-white px-3 py-2 text-sm text-gray-900 ring-1 ring-inset ring-gray-300">
                <span x-text="quantity"></span>
            </div>
            <button type="button"
                    @click="quantity = Math.min(10, quantity + 1)"
                    class="relative inline-flex items-center rounded-r-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                +
            </button>
        </div>
    </div>

    <!-- Total -->
    <div class="flex items-center justify-between border-t border-gray-200 pt-6">
        <h2 class="text-base font-medium text-gray-900">Total</h2>
        <div class="text-xl font-medium text-gray-900" x-show="selectedSize" x-text="formattedTotal"></div>
        <div class="text-sm text-gray-500 italic" x-show="!selectedSize">-</div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-8 flex flex-col space-y-4">
        <button type="submit"
                :disabled="!selectedSize"
                class="flex w-full items-center justify-center rounded-md border border-transparent bg-blue-600 px-8 py-3 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
            Add to Cart
        </button>
        <button type="submit"
                name="checkout"
                value="true"
                :disabled="!selectedSize"
                class="flex w-full items-center justify-center rounded-md border border-transparent bg-gray-900 px-8 py-3 text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
            Buy Now
        </button>
    </div>

    <!-- Trust Badges -->
    <div class="mt-6 grid grid-cols-3 gap-4 border-t border-gray-200 pt-6">
        <div class="text-center">
            <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <p class="mt-2 text-xs text-gray-500">Secure Payment</p>
        </div>
        <div class="text-center">
            <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            <p class="mt-2 text-xs text-gray-500">Free Shipping</p>
        </div>
        <div class="text-center">
            <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-2 text-xs text-gray-500">Quality Guarantee</p>
        </div>
    </div>
</div>

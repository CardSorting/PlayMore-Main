@props(['materials', 'selectedSize', 'sizeDetails' => null])

<div x-data="{ 
    selectedMaterial: '{{ old('material', 'matte') }}',
    quantity: 1,
    materials: @js($materials),
    sizeDetails: @js($sizeDetails ?? ['price' => 0]),
    get price() {
        const basePrice = this.sizeDetails.price / 100;
        const multiplier = this.materials[this.selectedMaterial].price_multiplier;
        return basePrice * multiplier;
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
    },
    get estimatedDelivery() {
        const today = new Date();
        const delivery = new Date(today.setDate(today.getDate() + 7));
        return delivery.toLocaleDateString('en-US', { 
            weekday: 'long',
            month: 'long',
            day: 'numeric'
        });
    }
}" class="space-y-6">
    <!-- Material Selection -->
    <div>
        <h3 class="text-base font-medium text-gray-900">Print Material</h3>
        <div class="mt-4 space-y-4">
            <template x-for="(details, material) in materials" :key="material">
                <label class="relative flex cursor-pointer rounded-xl border-2 bg-white p-5 focus:outline-none transition-all duration-200"
                       :class="{
                           'border-indigo-500 ring-2 ring-indigo-500 ring-offset-2 shadow-md': selectedMaterial === material,
                           'border-gray-200 hover:border-indigo-200 hover:shadow-sm': selectedMaterial !== material
                       }">
                    <input type="radio"
                           name="material"
                           :value="material"
                           x-model="selectedMaterial"
                           class="sr-only">
                    <div class="flex flex-1">
                        <div class="flex flex-col">
                            <span class="block text-sm font-semibold text-gray-900" x-text="details.name"></span>
                            <span class="mt-1.5 text-sm text-gray-500" x-text="details.description"></span>
                            <span class="mt-2 inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700" x-show="details.price_multiplier > 1">
                                +<span x-text="((details.price_multiplier - 1) * 100).toFixed(0)"></span>% premium
                            </span>
                        </div>
                    </div>
                </label>
            </template>
        </div>
    </div>

    <!-- Order Summary Card -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="p-6">
            <!-- Price Display -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <h2 class="text-base font-medium text-gray-900">Price</h2>
                <div class="text-xl font-medium text-gray-900" x-text="formattedPrice"></div>
            </div>

        </div>

        <!-- Order Details -->
        <div class="border-t border-gray-200 px-6 py-4">
            <!-- Quantity Selector -->
            <div class="flex items-center justify-between py-2">
                <label for="quantity" class="text-sm font-medium text-gray-700">Quantity</label>
                <div class="flex rounded-md shadow-sm">
                    <button type="button" 
                            @click="quantity = Math.max(1, quantity - 1)"
                            class="relative inline-flex items-center rounded-l-md bg-white px-2 py-1 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        -
                    </button>
                    <div class="relative flex w-12 items-center justify-center bg-white px-2 py-1 text-sm text-gray-900 ring-1 ring-inset ring-gray-300">
                        <span x-text="quantity"></span>
                    </div>
                    <button type="button"
                            @click="quantity = Math.min(10, quantity + 1)"
                            class="relative inline-flex items-center rounded-r-md bg-white px-2 py-1 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        +
                    </button>
                </div>
            </div>

            <!-- Total -->
            <div class="flex items-center justify-between border-t border-gray-200 py-4">
                <h2 class="text-base font-medium text-gray-900">Total</h2>
                <div class="text-xl font-medium text-gray-900" x-text="formattedTotal"></div>
            </div>

            <!-- Delivery Estimate -->
            <div class="border-t border-gray-200 py-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="ml-2 text-sm text-gray-500">
                        Estimated delivery by <span class="font-medium text-gray-900" x-text="estimatedDelivery"></span>
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3 pt-4">
                <!-- Express Checkout -->
                <button type="submit"
                        name="express_checkout"
                        value="true"
                        class="flex w-full items-center justify-center rounded-md border border-transparent bg-gray-900 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                    Buy now with Shop Pay
                </button>

                <!-- More Payment Options -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-white px-4 text-sm text-gray-500">More payment options</span>
                    </div>
                </div>

                <!-- Regular Checkout -->
                <button type="submit"
                        class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                    Add to Cart
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

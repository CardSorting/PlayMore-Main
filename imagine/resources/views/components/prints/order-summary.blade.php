@props(['order', 'maxQuantity'])

<div class="bg-gray-50 rounded-lg p-6 sticky top-6 transition-shadow duration-300 hover:shadow-md">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
    
    <div class="space-y-4">
        <!-- Order Image and Details -->
        <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <img src="{{ $order->gallery->image_url }}" alt="{{ $order->gallery->prompt }}" 
                        class="w-20 h-20 rounded-lg object-cover ring-1 ring-gray-200">
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">{{ $order->gallery->prompt }}</h4>
                    <p class="text-sm text-gray-500 mt-1">High quality print on premium paper</p>
                    <div class="flex items-center mt-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 text-green-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Quality guaranteed
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="space-y-3">
            <div class="flex justify-between">
                <dt class="text-sm text-gray-600">Print Size</dt>
                <dd class="text-sm font-medium text-gray-900">{{ $order->size }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm text-gray-600">Material</dt>
                <dd class="text-sm font-medium text-gray-900">{{ ucfirst($order->material) }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm text-gray-600">Quantity</dt>
                <dd class="text-sm font-medium text-gray-900" x-text="selectedQuantity">{{ $order->quantity ?? 1 }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm text-gray-600">Price per print</dt>
                <dd class="text-sm font-medium text-gray-900">${{ number_format($order->unit_price / 100, 2) }}</dd>
            </div>
        </div>

        <!-- Custom Quantity Input -->
        <x-prints.custom-quantity-input :maxQuantity="$maxQuantity" />

        <!-- Price Summary -->
        <div class="border-t border-gray-200 pt-4">
            <dl class="space-y-3">
                <template x-if="presetData[selectedQuantity]?.savingsAmount > 0">
                    <div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Original price</dt>
                            <dd class="text-sm text-gray-500 line-through" x-text="'$' + (presetData[selectedQuantity].originalPrice / 100).toFixed(2)"></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-green-700">You save</dt>
                            <dd class="text-sm font-bold text-green-700" x-text="'$' + (presetData[selectedQuantity].savingsAmount / 100).toFixed(2)"></dd>
                        </div>
                    </div>
                </template>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Subtotal</dt>
                    <dd class="text-sm font-medium text-gray-900" x-text="'$' + calculateTotal().toFixed(2)"></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Shipping</dt>
                    <dd class="text-sm font-medium text-gray-900">Free</dd>
                </div>
                <div class="flex justify-between border-t border-gray-200 pt-3 mb-4">
                    <dt class="text-base font-medium text-gray-900">Total</dt>
                    <dd class="text-base font-medium text-gray-900" x-text="'$' + calculateTotal().toFixed(2)"></dd>
                </div>
            </dl>
        </div>
    </div>
</div>

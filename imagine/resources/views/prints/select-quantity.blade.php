<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="mb-8">
                        <x-prints.progress-stepper :currentStep="3" />
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Select Quantity</h2>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Quantity Selection -->
                        <form action="{{ route('prints.update-quantity', $order) }}" method="POST">
                            @csrf
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">{{ $order->gallery->prompt }}</h3>
                                        <p class="text-sm text-gray-500">{{ $order->size }} - {{ ucfirst($order->material) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Unit Price</p>
                                        <p class="text-lg font-medium text-gray-900">${{ number_format($order->unit_price / 100, 2) }}</p>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <div class="mt-2 flex items-center">
                                        <select name="quantity" id="quantity" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            @for ($i = 1; $i <= $maxQuantity; $i++)
                                                <option value="{{ $i }}" {{ $order->quantity == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    @error('quantity')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-6">
                                    <button type="submit" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Continue to Checkout
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Order Summary -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $order->gallery->image_url }}" alt="{{ $order->gallery->prompt }}" class="w-20 h-20 rounded-lg object-cover">
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $order->gallery->prompt }}</h4>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Print Size</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $order->size }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Material</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ ucfirst($order->material) }}</dd>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <dl class="space-y-3">
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-600">Subtotal</dt>
                                            <dd class="text-sm font-medium text-gray-900" id="subtotal-price">${{ number_format($order->total_price / 100, 2) }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-600">Shipping</dt>
                                            <dd class="text-sm font-medium text-gray-900">Free</dd>
                                        </div>
                                        <div class="flex justify-between border-t border-gray-200 pt-3">
                                            <dt class="text-base font-medium text-gray-900">Total</dt>
                                            <dd class="text-base font-medium text-gray-900" id="total-price">${{ number_format($order->total_price / 100, 2) }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantitySelect = document.getElementById('quantity');
            const subtotalElement = document.getElementById('subtotal-price');
            const totalElement = document.getElementById('total-price');
            const unitPrice = {{ $order->unit_price }};

            function updateTotal() {
                const quantity = parseInt(quantitySelect.value);
                const total = (quantity * unitPrice / 100).toFixed(2);
                subtotalElement.textContent = '$' + total;
                totalElement.textContent = '$' + total;
            }

            // Set initial total
            updateTotal();

            // Update total when quantity changes
            quantitySelect.addEventListener('change', updateTotal);
        });
    </script>
    @endpush
</x-app-layout>

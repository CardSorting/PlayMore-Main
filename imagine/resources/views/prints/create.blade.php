<x-app-layout>
    <!-- Progress Bar -->
    <div class="border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex justify-center" aria-label="Progress">
                <ol role="list" class="flex items-center space-x-16 py-4">
                    <li class="flex items-center text-blue-600">
                        <span class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-blue-600 bg-white">
                            <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                        </span>
                        <span class="ml-3 text-sm font-medium">Details</span>
                    </li>
                    <li class="flex items-center text-gray-400">
                        <span class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white">
                            <span class="text-sm">2</span>
                        </span>
                        <span class="ml-3 text-sm font-medium">Payment</span>
                    </li>
                    <li class="flex items-center text-gray-400">
                        <span class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white">
                            <span class="text-sm">3</span>
                        </span>
                        <span class="ml-3 text-sm font-medium">Confirmation</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
                <!-- Product Information -->
                <div class="lg:col-span-1">
                    <div class="aspect-w-1 aspect-h-1 mb-6">
                        <img src="{{ $gallery->image_url }}" alt="{{ $gallery->prompt }}" class="w-full h-full object-cover rounded-lg shadow-lg">
                    </div>
                    <div class="mt-4">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Your Custom Print</h2>
                        <p class="text-gray-600">{{ $gallery->prompt }}</p>
                    </div>
                </div>

                <!-- Order Form -->
                <div class="mt-10 lg:mt-0">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <form action="{{ route('prints.store', $gallery) }}" method="POST" class="space-y-8">
                    @csrf

                            <!-- Print Size Selection -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Select Print Size</h3>
                                <div class="grid grid-cols-1 gap-4">
                            @foreach($sizes as $key => $size)
                                <label class="relative flex cursor-pointer rounded-lg border bg-white p-5 shadow-sm hover:border-gray-300 focus:outline-none">
                                    <input type="radio" name="size" value="{{ $key }}" class="sr-only" aria-labelledby="size-{{ $key }}-label" required>
                                    <div class="flex flex-1 items-center justify-between">
                                        <div class="flex flex-col">
                                            <span id="size-{{ $key }}-label" class="block text-sm font-medium text-gray-900">{{ $size['name'] }}</span>
                                            <span class="mt-1.5 text-sm text-gray-500">Perfect for {{ $size['name'] === '8x10' ? 'standard frames and displays' : 'larger wall displays and galleries' }}</span>
                                        </div>
                                        <div class="ml-4 flex flex-col items-end">
                                            <span class="text-lg font-medium text-gray-900">${{ number_format($size['price'], 2) }}</span>
                                            <span class="mt-1 text-xs text-gray-500">Free shipping</span>
                                        </div>
                                    </div>
                                    <span class="pointer-events-none absolute -inset-px rounded-lg border-2 border-gray-200" aria-hidden="true"></span>
                                </label>
                            @endforeach
                        </div>
                        @error('size')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                            <!-- Shipping Information -->
                            <div class="border-t border-gray-200 pt-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-6">Shipping Information</h3>
                                
                                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                            <label for="shipping_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="shipping_name" id="shipping_name" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('shipping_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                                    <div class="sm:col-span-2">
                                        <label for="shipping_address" class="block text-sm font-medium text-gray-700">Street Address</label>
                                        <div class="mt-1">
                                            <input type="text" name="shipping_address" id="shipping_address" required
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                   placeholder="Enter your street address">
                                        </div>
                            @error('shipping_address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                                    <div>
                                        <label for="shipping_city" class="block text-sm font-medium text-gray-700">City</label>
                                        <div class="mt-1">
                                            <input type="text" name="shipping_city" id="shipping_city" required
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                   placeholder="Enter city">
                                        </div>
                                @error('shipping_city')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                                    <div>
                                        <label for="shipping_state" class="block text-sm font-medium text-gray-700">State / Province</label>
                                        <div class="mt-1">
                                            <input type="text" name="shipping_state" id="shipping_state" required
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                   placeholder="Enter state">
                                        </div>
                                @error('shipping_state')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                                    <div>
                                        <label for="shipping_zip" class="block text-sm font-medium text-gray-700">ZIP / Postal Code</label>
                                        <div class="mt-1">
                                            <input type="text" name="shipping_zip" id="shipping_zip" required
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                   placeholder="Enter ZIP code">
                                        </div>
                                @error('shipping_zip')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                                    <div>
                                        <label for="shipping_country" class="block text-sm font-medium text-gray-700">Country</label>
                                        <div class="mt-1">
                                            <select name="shipping_country" id="shipping_country" required
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                                <option value="">Select a country</option>
                                                <option value="US">United States</option>
                                                <option value="CA">Canada</option>
                                                <option value="GB">United Kingdom</option>
                                                <option value="AU">Australia</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @error('shipping_country')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                            <!-- Order Summary -->
                            <div class="mt-8 border-t border-gray-200 pt-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                                <dl class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <dt class="text-sm text-gray-600">Subtotal</dt>
                                        <dd class="text-sm font-medium text-gray-900">$<span id="subtotal">0.00</span></dd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dt class="text-sm text-gray-600">Shipping</dt>
                                        <dd class="text-sm font-medium text-gray-900">Free</dd>
                                    </div>
                                    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                                        <dt class="text-base font-medium text-gray-900">Order total</dt>
                                        <dd class="text-base font-medium text-gray-900">$<span id="total">0.00</span></dd>
                                    </div>
                                </dl>

                                <div class="mt-8">
                                    <button type="submit" class="w-full flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Continue to Payment
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sizeInputs = document.querySelectorAll('input[name="size"]');
    const labels = document.querySelectorAll('.relative.flex.cursor-pointer');
    const subtotalSpan = document.getElementById('subtotal');
    const totalSpan = document.getElementById('total');
    const sizes = @json($sizes);

    function updatePrice(value) {
        if (value && sizes[value]) {
            const price = sizes[value].price;
            subtotalSpan.textContent = price.toFixed(2);
            totalSpan.textContent = price.toFixed(2);
        } else {
            subtotalSpan.textContent = '0.00';
            totalSpan.textContent = '0.00';
        }
    }

    sizeInputs.forEach((input, index) => {
        input.addEventListener('change', function() {
            // Remove selected styles from all labels
            labels.forEach(label => {
                label.querySelector('span[aria-hidden="true"]').classList.remove('border-blue-600');
                label.querySelector('span[aria-hidden="true"]').classList.add('border-gray-200');
            });

            // Add selected styles to checked input's label
            if (this.checked) {
                labels[index].querySelector('span[aria-hidden="true"]').classList.remove('border-gray-200');
                labels[index].querySelector('span[aria-hidden="true"]').classList.add('border-blue-600');
                updatePrice(this.value);
            }
        });
    });

    // Set initial price if a size is pre-selected
    const selectedSize = document.querySelector('input[name="size"]:checked');
    if (selectedSize) {
        updatePrice(selectedSize.value);
    }
});
</script>
@endpush
</x-app-layout>

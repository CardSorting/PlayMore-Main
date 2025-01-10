<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <x-prints.progress-stepper :currentStep="3" />
    </div>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <x-prints.order-summary :order="$printOrder" :maxQuantity="250" />
                </div>

                <!-- Shipping Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <form action="{{ route('prints.store-shipping-address', $printOrder) }}" method="POST">
                            @csrf
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900">Shipping Address</h3>

                                <div>
                                    <label for="shipping_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" name="shipping_name" id="shipping_name" value="{{ old('shipping_name') }}" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    @error('shipping_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700">Street Address</label>
                                    <input type="text" name="shipping_address" id="shipping_address" value="{{ old('shipping_address') }}" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    @error('shipping_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label for="shipping_city" class="block text-sm font-medium text-gray-700">City</label>
                                        <input type="text" name="shipping_city" id="shipping_city" value="{{ old('shipping_city') }}" required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        @error('shipping_city')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="shipping_state" class="block text-sm font-medium text-gray-700">State</label>
                                        <input type="text" name="shipping_state" id="shipping_state" value="{{ old('shipping_state') }}" required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        @error('shipping_state')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label for="shipping_zip" class="block text-sm font-medium text-gray-700">ZIP / Postal Code</label>
                                        <input type="text" name="shipping_zip" id="shipping_zip" value="{{ old('shipping_zip') }}" required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        @error('shipping_zip')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="shipping_country" class="block text-sm font-medium text-gray-700">Country</label>
                                        <select name="shipping_country" id="shipping_country" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="">Select a country</option>
                                            <option value="US" {{ old('shipping_country') == 'US' ? 'selected' : '' }}>United States</option>
                                            <option value="CA" {{ old('shipping_country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                        </select>
                                        @error('shipping_country')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <button type="submit"
                                            class="w-full flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
</x-app-layout>

@props(['errors'])

<div x-data="{ 
    countries: {
        'US': 'United States',
        'CA': 'Canada',
        'GB': 'United Kingdom',
        'AU': 'Australia'
    },
    states: {
        'US': @json(config('location.states.us')),
        'CA': @json(config('location.states.ca')),
        'GB': @json(config('location.states.gb')),
        'AU': @json(config('location.states.au'))
    },
    selectedCountry: old('shipping_country', 'US'),
    getStates() {
        return this.states[this.selectedCountry] || {};
    }
}" class="space-y-6">
    <div class="border-b border-gray-200 pb-6">
        <h2 class="text-lg font-medium text-gray-900">Shipping Information</h2>
        <p class="mt-1 text-sm text-gray-500">Please enter your shipping details.</p>
    </div>

    <!-- Full Name -->
    <div>
        <label for="shipping_name" class="block text-sm font-medium text-gray-700">Full Name</label>
        <div class="mt-1">
            <input type="text"
                   name="shipping_name"
                   id="shipping_name"
                   value="{{ old('shipping_name') }}"
                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('shipping_name') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                   required>
        </div>
        @error('shipping_name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Street Address -->
    <div>
        <label for="shipping_address" class="block text-sm font-medium text-gray-700">Street Address</label>
        <div class="mt-1">
            <input type="text"
                   name="shipping_address"
                   id="shipping_address"
                   value="{{ old('shipping_address') }}"
                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('shipping_address') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                   required>
        </div>
        @error('shipping_address')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- City and State/Province -->
    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
        <div>
            <label for="shipping_city" class="block text-sm font-medium text-gray-700">City</label>
            <div class="mt-1">
                <input type="text"
                       name="shipping_city"
                       id="shipping_city"
                       value="{{ old('shipping_city') }}"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('shipping_city') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                       required>
            </div>
            @error('shipping_city')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="shipping_state" class="block text-sm font-medium text-gray-700">State / Province</label>
            <div class="mt-1">
                <select name="shipping_state"
                        id="shipping_state"
                        x-model="selectedState"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('shipping_state') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @enderror"
                        required>
                    <option value="">Select...</option>
                    <template x-for="(name, code) in getStates()" :key="code">
                        <option :value="code" x-text="name"></option>
                    </template>
                </select>
            </div>
            @error('shipping_state')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- ZIP/Postal Code and Country -->
    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
        <div>
            <label for="shipping_zip" class="block text-sm font-medium text-gray-700">ZIP / Postal Code</label>
            <div class="mt-1">
                <input type="text"
                       name="shipping_zip"
                       id="shipping_zip"
                       value="{{ old('shipping_zip') }}"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('shipping_zip') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                       required>
            </div>
            @error('shipping_zip')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="shipping_country" class="block text-sm font-medium text-gray-700">Country</label>
            <div class="mt-1">
                <select name="shipping_country"
                        id="shipping_country"
                        x-model="selectedCountry"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('shipping_country') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @enderror"
                        required>
                    <option value="">Select...</option>
                    <template x-for="(name, code) in countries" :key="code">
                        <option :value="code" x-text="name"></option>
                    </template>
                </select>
            </div>
            @error('shipping_country')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Special Instructions -->
    <div>
        <label for="notes" class="block text-sm font-medium text-gray-700">
            Special Instructions (Optional)
        </label>
        <div class="mt-1">
            <textarea name="notes"
                      id="notes"
                      rows="3"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('notes') }}</textarea>
        </div>
        <p class="mt-2 text-sm text-gray-500">Add any special instructions for shipping or handling.</p>
    </div>

    <!-- Trust Badges -->
    <div class="mt-8 border-t border-gray-200 pt-6">
        <div class="flex items-center justify-center space-x-6">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="ml-2 text-sm text-gray-500">Secure Checkout</span>
            </div>
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" />
                </svg>
                <span class="ml-2 text-sm text-gray-500">Free Shipping</span>
            </div>
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                </svg>
                <span class="ml-2 text-sm text-gray-500">Quality Guaranteed</span>
            </div>
        </div>
    </div>
</div>

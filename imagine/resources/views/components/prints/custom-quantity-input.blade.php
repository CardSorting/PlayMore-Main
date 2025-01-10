@props(['maxQuantity'])

<div class="mb-4">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-base font-medium text-gray-900">Need a Different Amount?</h3>
            <p class="mt-1 text-sm text-gray-500">Enter any quantity up to {{ $maxQuantity }} prints</p>
        </div>
    </div>
    <div class="flex items-center space-x-3">
        <div class="relative flex-1">
            <input type="number" 
                name="custom_quantity" 
                id="custom_quantity" 
                min="1" 
                max="{{ $maxQuantity }}"
                x-on:input="validateCustomQuantity($event.target)"
                class="block w-full rounded-md border-gray-300 pl-4 pr-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                placeholder="Enter amount">
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <span class="text-gray-500 sm:text-sm">qty</span>
            </div>
        </div>
        <button type="button" 
            id="apply_custom" 
            x-on:click="applyCustomQuantity"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 hover:shadow disabled:opacity-50 disabled:cursor-not-allowed">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Apply
        </button>
    </div>
    @error('quantity')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

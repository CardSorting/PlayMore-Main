@props(['order', 'maxQuantity'])

<div>
    <form id="quantity-form" 
        action="{{ route('prints.update-quantity', ['order' => $order]) }}" 
        method="POST"
        x-data="{ quantity: {{ $order->quantity ?? 1 }} }">
        @csrf
        <input type="hidden" name="final_price" x-bind:value="{{ $order->unit_price }} * quantity">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Select Quantity</h2>
            <p class="mt-2 text-sm text-gray-500">{{ $order->size }} - {{ ucfirst($order->material) }}</p>
        </div>

        <!-- Simple Quantity Input -->
        <div class="max-w-xs mx-auto">
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
            <input type="number" 
                name="quantity" 
                id="quantity"
                x-model="quantity"
                min="1"
                max="{{ $maxQuantity }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                required>
        </div>

        <!-- Error Messages -->
        @error('quantity')
            <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
        @enderror
        @error('final_price')
            <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
        @enderror

        <!-- Continue Button -->
        <div class="mt-6 max-w-xs mx-auto">
            <button type="submit" 
                class="w-full flex justify-center items-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Continue to Checkout
            </button>
        </div>
    </form>
</div>

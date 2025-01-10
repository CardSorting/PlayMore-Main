@props(['order', 'presets'])

<div>
    <form id="quantity-form" 
        x-ref="quantityForm" 
        action="{{ route('prints.update-quantity', ['order' => $order]) }}" 
        method="POST"
        @submit.prevent="
            const price = presetData[selectedQuantity] ? presetData[selectedQuantity].discountedPrice : (selectedQuantity * unitPrice);
            $refs.finalPrice.value = price;
            $event.target.submit();
        ">
        @csrf
        <input type="hidden" name="final_price" id="final_price" x-ref="finalPrice">
        <input type="hidden" name="quantity" x-bind:value="selectedQuantity">
        <div>
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Select Quantity</h2>
                    <p class="mt-2 text-sm text-gray-500">{{ $order->size }} - {{ ucfirst($order->material) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Unit Price</p>
                    <p class="text-lg font-medium text-gray-900">${{ number_format($order->unit_price / 100, 2) }}</p>
                </div>
            </div>

            <!-- Quantity Tabs -->
            @php
                $activeTab = 'personal';
                if ($order->quantity > 5 && $order->quantity <= 50) {
                    $activeTab = 'professional';
                } elseif ($order->quantity > 50) {
                    $activeTab = 'wholesale';
                }
            @endphp
            <x-prints.quantity-tabs :activeTab="$activeTab">
                <x-slot name="personal">
                    @foreach ($presets as $preset)
                        @if($preset->amount <= 5)
                            <x-prints.quantity-preset-card :preset="$preset" :order="$order" />
                        @endif
                    @endforeach
                </x-slot>

                <x-slot name="professional">
                    @foreach ($presets as $preset)
                        @if($preset->amount > 5 && $preset->amount <= 50)
                            <x-prints.quantity-preset-card :preset="$preset" :order="$order" />
                        @endif
                    @endforeach
                </x-slot>

                <x-slot name="wholesale">
                    @foreach ($presets as $preset)
                        @if($preset->amount > 50)
                            <x-prints.quantity-preset-card :preset="$preset" :order="$order" />
                        @endif
                    @endforeach
                </x-slot>
            </x-prints.quantity-tabs>
        </div>

        <!-- Continue Button -->
        <div class="mt-6">
            <button type="submit" 
                x-bind:disabled="!selectedQuantity"
                x-bind:class="{ 'opacity-50 cursor-not-allowed': !selectedQuantity }"
                class="w-full flex justify-center items-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Continue to Checkout
            </button>
        </div>
    </form>
</div>

<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="mb-8">
                        <x-prints.progress-stepper :currentStep="3" />
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" 
                        x-data="quantitySelector(@js($presets->mapWithKeys(fn($preset) => [
                            $preset->amount => [
                                'originalPrice' => $preset->originalPrice,
                                'discountedPrice' => $preset->discountedPrice,
                                'savingsAmount' => $preset->savingsAmount,
                                'savings' => $preset->savings
                            ]
                        ])->toArray()), {{ $order->unit_price }})">
                        <!-- Quantity Selection -->
                        <x-prints.quantity-form :order="$order" :presets="$presets" />

                        <!-- Order Summary -->
                        <x-prints.order-summary :order="$order" :maxQuantity="$maxQuantity" />
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="mb-8">
                        <x-prints.progress-stepper :currentStep="3" />
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" 
                        x-data="{ 
                            selectedQuantity: {{ $order->quantity ?? 1 }},
                            unitPrice: {{ $order->unit_price }},
                            presetData: @js($presets->mapWithKeys(fn($preset) => [
                                $preset->amount => [
                                    'originalPrice' => $preset->originalPrice,
                                    'discountedPrice' => $preset->discountedPrice,
                                    'savingsAmount' => $preset->savingsAmount,
                                    'savings' => $preset->savings
                                ]
                            ])->toArray()),
                            customQuantityValid: false,
                            calculateTotal() {
                                if (this.presetData[this.selectedQuantity]) {
                                    return this.presetData[this.selectedQuantity].discountedPrice / 100;
                                }
                                return (this.selectedQuantity * this.unitPrice) / 100;
                            },
                            validateCustomQuantity(input) {
                                const value = parseInt(input.value);
                                const maxQuantity = parseInt(input.max);
                                
                                if (!isNaN(value) && value >= 1 && value <= maxQuantity) {
                                    input.classList.remove('border-red-300');
                                    input.classList.add('border-green-300');
                                    this.customQuantityValid = true;
                                    return true;
                                }
                                
                                input.classList.remove('border-green-300');
                                input.classList.add('border-red-300');
                                this.customQuantityValid = false;
                                return false;
                            },
                            applyCustomQuantity() {
                                if (!this.customQuantityValid) return;
                                const input = document.getElementById('custom_quantity');
                                const value = parseInt(input.value);
                                if (value && value >= 1 && value <= input.max) {
                                    this.selectedQuantity = value;
                                    const btn = document.getElementById('apply_custom');
                                    btn.classList.add('bg-green-500');
                                    btn.querySelector('svg')?.classList.add('animate-bounce');
                                    setTimeout(() => {
                                        btn.classList.remove('bg-green-500');
                                        btn.querySelector('svg')?.classList.remove('animate-bounce');
                                    }, 1000);
                                }
                            }
                        }">
                        <!-- Quantity Selection -->
                        <x-prints.quantity-form :order="$order" :presets="$presets" :maxQuantity="$maxQuantity" />

                        <!-- Order Summary -->
                        <x-prints.order-summary :order="$order" :maxQuantity="$maxQuantity" />
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

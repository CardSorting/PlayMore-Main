<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="mb-8">
                        <x-prints.progress-stepper :currentStep="3" />
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Quantity Selection -->
                        <form id="quantity-form" x-ref="quantityForm" action="{{ route('prints.update-quantity', ['order' => $order]) }}" method="POST" x-data="{ selectedQuantity: {{ $order->quantity ?? 1 }} }">
                            @csrf
                            <input type="hidden" name="final_price" id="final_price">
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
                                <button type="submit" form="quantity-form" 
                                    x-bind:disabled="!selectedQuantity"
                                    x-bind:class="{ 'opacity-50 cursor-not-allowed': !selectedQuantity }"
                                    class="w-full flex justify-center items-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Continue to Checkout
                                </button>
                            </div>
                        </form>

                        <!-- Order Summary -->
                        <div class="bg-gray-50 rounded-lg p-6 sticky top-6 transition-shadow duration-300 hover:shadow-md">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                            
                            <div class="space-y-4">
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
                                        <dd class="text-sm font-medium text-gray-900" id="quantity-display">{{ $order->quantity ?? 1 }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Price per print</dt>
                                        <dd class="text-sm font-medium text-gray-900">${{ number_format($order->unit_price / 100, 2) }}</dd>
                                    </div>
                                </div>

                                <!-- Custom Quantity -->
                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <h3 class="text-base font-medium text-gray-900">Need a Different Amount?</h3>
                                            <p class="mt-1 text-sm text-gray-500">Enter any quantity up to {{ $maxQuantity }} prints</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="relative flex-1">
                                            <input type="number" name="custom_quantity" id="custom_quantity" min="1" max="{{ $maxQuantity }}"
                                                class="block w-full rounded-md border-gray-300 pl-4 pr-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                placeholder="Enter amount">
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                                <span class="text-gray-500 sm:text-sm">qty</span>
                                            </div>
                                        </div>
                                        <button type="button" id="apply_custom" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 hover:shadow">
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

                                <div class="border-t border-gray-200 pt-4">
                                    <dl class="space-y-3">
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-600">Subtotal</dt>
                                            <dd class="text-sm font-medium text-gray-900" id="subtotal-price">${{ number_format($order->total_price / 100, 2) }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-600">Shipping</dt>
                                            <dd class="text-sm font-medium text-gray-900">Free</dd>
                                        </div>
                                        <div class="flex justify-between border-t border-gray-200 pt-3 mb-4">
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
            const subtotalElement = document.getElementById('subtotal-price');
            const totalElement = document.getElementById('total-price');
            const customQuantityInput = document.getElementById('custom_quantity');
            const applyCustomBtn = document.getElementById('apply_custom');
            const quantityInputs = document.querySelectorAll('input[name="quantity"]');
            const unitPrice = {{ $order->unit_price }};
            const presetData = {
                @foreach ($presets as $preset)
                    {{ $preset->amount }}: {
                        originalPrice: {{ $preset->originalPrice }},
                        discountedPrice: {{ $preset->discountedPrice }},
                        savingsAmount: {{ $preset->savingsAmount }},
                        savings: "{{ $preset->savings }}"
                    },
                @endforeach
            };

            function animateNumber(element, start, end, duration = 500) {
                const startTime = performance.now();
                const change = end - start;

                function update(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    // Easing function for smooth animation
                    const easeOutQuad = 1 - Math.pow(1 - progress, 2);
                    const current = start + (change * easeOutQuad);
                    
                    element.textContent = '$' + current.toFixed(2);

                    if (progress < 1) {
                        requestAnimationFrame(update);
                    }
                }

                requestAnimationFrame(update);
            }

            function updateTotal(quantity, animate = true) {
                let newTotal;
                const preset = presetData[quantity];
                
                // Update quantity display
                document.getElementById('quantity-display').textContent = quantity;

                if (preset) {
                    newTotal = preset.discountedPrice / 100;
                    
                    // Show savings if available
                    const savingsRow = document.getElementById('savings-row');
                    const originalRow = document.getElementById('original-row');
                    
                    if (preset.savingsAmount > 0) {
                        if (!savingsRow) {
                            // Insert savings row before total
                            const dl = document.querySelector('dl.space-y-3');
                            const savingsHTML = `
                                <div id="original-row" class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Original price</dt>
                                    <dd class="text-sm text-gray-500 line-through">$${(preset.originalPrice / 100).toFixed(2)}</dd>
                                </div>
                                <div id="savings-row" class="flex justify-between">
                                    <dt class="text-sm font-medium text-green-700">You save</dt>
                                    <dd class="text-sm font-bold text-green-700">$${(preset.savingsAmount / 100).toFixed(2)}</dd>
                                </div>
                            `;
                            dl.insertAdjacentHTML('afterbegin', savingsHTML);
                        } else {
                            // Update existing savings rows
                            originalRow.querySelector('dd').textContent = '$' + (preset.originalPrice / 100).toFixed(2);
                            savingsRow.querySelector('dd').textContent = '$' + (preset.savingsAmount / 100).toFixed(2);
                        }
                    } else {
                        // Remove savings rows if they exist
                        savingsRow?.remove();
                        originalRow?.remove();
                    }
                } else {
                    // No preset discount, calculate regular price
                    newTotal = (quantity * unitPrice / 100);
                    // Remove any existing savings rows
                    document.getElementById('savings-row')?.remove();
                    document.getElementById('original-row')?.remove();
                }

                const currentTotal = parseFloat(subtotalElement.textContent.replace('$', ''));

                // Update hidden price input
                document.getElementById('final_price').value = Math.round(newTotal * 100);

                if (animate) {
                    animateNumber(subtotalElement, currentTotal, newTotal);
                    animateNumber(totalElement, currentTotal, newTotal);
                } else {
                    subtotalElement.textContent = '$' + newTotal.toFixed(2);
                    totalElement.textContent = '$' + newTotal.toFixed(2);
                }
            }

            // Add highlight effect when quantity changes
            function addHighlightEffect() {
                const summary = document.querySelector('.bg-gray-50');
                summary.classList.add('ring-2', 'ring-indigo-200');
                setTimeout(() => {
                    summary.classList.remove('ring-2', 'ring-indigo-200');
                }, 800);
            }

            // Watch Alpine.js state changes
            Alpine.effect(() => {
                const form = document.getElementById('quantity-form');
                const alpineComponent = Alpine.$data(form);
                if (alpineComponent.selectedQuantity) {
                    updateTotal(alpineComponent.selectedQuantity);
                    customQuantityInput.value = ''; // Clear custom input
                    addHighlightEffect();
                }
            });

            // Handle custom quantity with validation feedback
            customQuantityInput.addEventListener('input', function() {
                const value = parseInt(this.value);
                if (value && value >= 1 && value <= {{ $maxQuantity }}) {
                    this.classList.remove('border-red-300');
                    this.classList.add('border-green-300');
                    applyCustomBtn.disabled = false;
                    applyCustomBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    this.classList.remove('border-green-300');
                    this.classList.add('border-red-300');
                    applyCustomBtn.disabled = true;
                    applyCustomBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });

            applyCustomBtn.addEventListener('click', function() {
                if (this.disabled) return;

                const customValue = parseInt(customQuantityInput.value);
                if (customValue && customValue >= 1 && customValue <= {{ $maxQuantity }}) {
                    // Update Alpine.js state and submit form
                    const form = document.getElementById('quantity-form');
                    const alpineComponent = Alpine.$data(form);
                    alpineComponent.selectedQuantity = customValue;
                    updateTotal(customValue);
                    // Don't auto-submit, just update the state and price

                    // Success feedback
                    const btn = this;
                    btn.classList.add('bg-green-500');
                    btn.querySelector('svg').classList.add('animate-bounce');
                    setTimeout(() => {
                        btn.classList.remove('bg-green-500');
                        btn.querySelector('svg').classList.remove('animate-bounce');
                    }, 1000);
                }
            });

            // Set initial total without animation
            const form = document.getElementById('quantity-form');
            const alpineComponent = Alpine.$data(form);
            updateTotal(alpineComponent.selectedQuantity, false);

            // Handle Enter key in custom quantity input
            customQuantityInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (!applyCustomBtn.disabled) {
                        applyCustomBtn.click();
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>

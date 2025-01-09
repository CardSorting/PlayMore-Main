<x-app-layout>
    <div class="py-12" 
        x-data="{
            selectedAmount: {{ $defaultOption['amount'] }},
            selectedPrice: {{ $defaultOption['price'] }},
            
            init() {
                if (window.paypal) {
                    this.initPayPalButton();
                } else {
                    // Wait for PayPal SDK to load
                    const checkPayPal = setInterval(() => {
                        if (window.paypal) {
                            clearInterval(checkPayPal);
                            this.initPayPalButton();
                        }
                    }, 100);
                }
            },

            selectOption(amount, price) {
                this.selectedAmount = amount;
                this.selectedPrice = price;
                document.getElementById('paypal-button-container').innerHTML = '';
                this.initPayPalButton();
            },

            showMessage(message) {
                document.getElementById('result-message').innerHTML = message;
            },

            async initPayPalButton() {
                try {
                    const buttons = paypal.Buttons({
                        style: {
                            shape: 'pill',
                            layout: 'horizontal',
                            height: 55
                        },
                        createOrder: async () => {
                            try {
                                const response = await fetch('/api/orders', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                    },
                                    body: JSON.stringify({
                                        cart: [{
                                            id: `pulse_${this.selectedAmount}`,
                                            quantity: 1,
                                            price: this.selectedPrice
                                        }]
                                    })
                                });

                                if (!response.ok) {
                                    const errorData = await response.json();
                                    throw new Error(errorData.message || 'Failed to create order');
                                }

                                const data = await response.json();
                                if (!data.id) {
                                    throw new Error('Invalid order response');
                                }

                                return data.id;
                            } catch (error) {
                                console.error('Create order error:', error);
                                this.showMessage(`Failed to create order: ${error.message}`);
                                throw error;
                            }
                        },
                        onApprove: async (data) => {
                            try {
                                const response = await fetch(`/api/orders/${data.orderID}/capture`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                    },
                                    body: JSON.stringify({
                                        amount: this.selectedAmount
                                    })
                                });

                                if (!response.ok) {
                                    const errorData = await response.json();
                                    throw new Error(errorData.message || 'Failed to capture payment');
                                }

                                const result = await response.json();
                                if (result.status === 'COMPLETED') {
                                    this.showMessage('Payment successful! Your Pulse has been added.');
                                    setTimeout(() => window.location.reload(), 2000);
                                } else {
                                    throw new Error(`Payment not completed: ${result.status}`);
                                }
                            } catch (error) {
                                console.error('Capture error:', error);
                                this.showMessage(`Payment failed: ${error.message}`);
                                throw error;
                            }
                        },
                        onError: (err) => {
                            console.error('PayPal error:', err);
                            this.showMessage(`Payment error: ${err.message}`);
                        },
                        onCancel: () => {
                            this.showMessage('Payment cancelled. Please try again.');
                        }
                    });

                    if (!buttons.isEligible()) {
                        throw new Error('PayPal Buttons are not eligible');
                    }

                    await buttons.render('#paypal-button-container');
                } catch (error) {
                    console.error('PayPal initialization error:', error);
                    this.showMessage(`Failed to initialize payment system: ${error.message}`);
                }
            }
        }"
        x-init="init">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-semibold mb-2">Purchase Pulse</h2>
                        <p class="text-gray-600">Select an amount below</p>
                    </div>

                    <!-- Amount Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 max-w-3xl mx-auto">
                        @foreach($pulseOptions as $option)
                        <button 
                            @click="selectOption({{ $option['amount'] }}, {{ $option['price'] }})"
                            @keydown.space.prevent="selectOption({{ $option['amount'] }}, {{ $option['price'] }})"
                            :class="{ 'ring-2 ring-blue-500 border-blue-500': selectedAmount === {{ $option['amount'] }} }"
                            class="p-4 border rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500 hover:border-blue-300 transition-all duration-150"
                            tabindex="0"
                            role="radio"
                            :aria-checked="selectedAmount === {{ $option['amount'] }}"
                            aria-label="{{ number_format($option['amount']) }} Pulse for ${{ number_format($option['price'], 2) }}">
                            <div class="text-2xl font-bold text-blue-600 mb-1">{{ number_format($option['amount']) }}</div>
                            <div class="text-sm text-gray-600 mb-2">Pulse</div>
                            <div class="text-lg font-semibold">${{ number_format($option['price'], 2) }}</div>
                        </button>
                        @endforeach
                    </div>

                    <!-- Selected Amount Display -->
                    <div class="text-center mb-8">
                        <p class="text-lg text-gray-600">
                            Selected: <span class="font-semibold" x-text="selectedAmount.toLocaleString()"></span> Pulse 
                            for $<span class="font-semibold" x-text="selectedPrice.toFixed(2)"></span>
                        </p>
                    </div>

                    <!-- PayPal Button Container -->
                    <div id="paypal-button-container" class="max-w-md mx-auto"></div>

                    <!-- Result Message -->
                    <div id="result-message" class="mt-4 text-center text-gray-600"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency=USD&components=buttons&enable-funding=venmo,paylater&disable-funding=card"></script>
    @endpush
</x-app-layout>

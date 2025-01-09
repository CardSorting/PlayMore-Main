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
                            layout: 'horizontal'
                        },
                        createOrder: async () => {
                            const response = await fetch('/api/orders', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
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

                            const data = await response.json();
                            if (!data.id) throw new Error('Failed to create order');
                            return data.id;
                        },
                        onApprove: async (data) => {
                            const response = await fetch(`/api/orders/${data.orderID}/capture`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                },
                                body: JSON.stringify({
                                    amount: this.selectedAmount
                                })
                            });

                            const result = await response.json();
                            if (result.status === 'COMPLETED') {
                                this.showMessage('Payment successful! Your Pulse has been added.');
                                setTimeout(() => window.location.reload(), 2000);
                            }
                        },
                        onError: (err) => {
                            console.error('PayPal error:', err);
                            this.showMessage('Payment failed. Please try again.');
                        }
                    });

                    await buttons.render('#paypal-button-container');
                } catch (error) {
                    console.error('Failed to initialize PayPal:', error);
                    this.showMessage('Failed to initialize payment system. Please refresh the page.');
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

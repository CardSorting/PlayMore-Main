<x-app-layout>
    <div class="py-12" 
        x-data="pulsePayment"
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
    <script>
        // Only load PayPal SDK if not already loaded
        if (!window.paypal) {
            const script = document.createElement('script');
            script.src = "https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency=USD";
            script.async = true;
            document.head.appendChild(script);
        }
    </script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pulsePayment', () => ({
                selectedAmount: {{ $defaultOption['amount'] }},
                selectedPrice: {{ $defaultOption['price'] }},
                paypalLoaded: false,
                
                init() {
                    this.waitForPayPal();
                },

                waitForPayPal() {
                    if (window.paypal) {
                        this.initPayPalButton();
                    } else {
                        setTimeout(() => this.waitForPayPal(), 100);
                    }
                },

                selectOption(amount, price) {
                    this.selectedAmount = amount;
                    this.selectedPrice = price;
                    // Re-render PayPal button with new amount
                    document.getElementById('paypal-button-container').innerHTML = '';
                    this.initPayPalButton();
                },

                initPayPalButton() {
                    const self = this;
                    try {
                        paypal.Buttons({
                            style: {
                                shape: "pill",
                                layout: "horizontal",
                            },
                            async createOrder() {
                                try {
                                    const response = await fetch("/api/orders", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                                        },
                                        body: JSON.stringify({
                                            cart: [
                                                {
                                                    id: `pulse_${self.selectedAmount}`,
                                                    quantity: 1,
                                                    price: self.selectedPrice
                                                },
                                            ],
                                        }),
                                    });

                                    if (!response.ok) {
                                        const errorData = await response.json();
                                        throw new Error(errorData.error || 'Failed to create order');
                                    }

                                    const orderData = await response.json();

                                    if (!orderData || !orderData.id) {
                                        throw new Error('Invalid order response from server');
                                    }

                                    return orderData.id;
                                } catch (error) {
                                    console.error('PayPal createOrder error:', error);
                                    self.showMessage(`Could not initiate PayPal Checkout: ${error.message}`);
                                    throw error; // Rethrow to prevent PayPal button from proceeding
                                }
                            },
                            async onApprove(data, actions) {
                                try {
                                    const response = await fetch(`/api/orders/${data.orderID}/capture`, {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                                        },
                                        body: JSON.stringify({
                                            amount: self.selectedAmount
                                        }),
                                    });

                                    if (!response.ok) {
                                        const errorData = await response.json();
                                        throw new Error(errorData.error || 'Failed to capture order');
                                    }

                                    const orderData = await response.json();
                                    const errorDetail = orderData?.details?.[0];

                                    if (errorDetail?.issue === "INSTRUMENT_DECLINED") {
                                        return actions.restart();
                                    } else if (errorDetail) {
                                        throw new Error(`${errorDetail.description} (${orderData.debug_id})`);
                                    } else if (!orderData.purchase_units) {
                                        throw new Error(JSON.stringify(orderData));
                                    }

                                    const transaction =
                                        orderData?.purchase_units?.[0]?.payments?.captures?.[0] ||
                                        orderData?.purchase_units?.[0]?.payments?.authorizations?.[0];

                                    if (!transaction) {
                                        throw new Error('Transaction details not found in response');
                                    }

                                    self.showMessage(
                                        `Transaction ${transaction.status}: ${transaction.id}<br><br>Your Pulse has been added to your account!`,
                                    );
                                    // Refresh the page after a successful transaction
                                    setTimeout(() => window.location.reload(), 3000);
                                } catch (error) {
                                    console.error('PayPal capture error:', error);
                                    self.showMessage(
                                        `Sorry, your transaction could not be processed: ${error.message}`,
                                    );
                                    throw error; // Rethrow to prevent PayPal from considering this a success
                                }
                            },
                            onError(err) {
                                console.error('PayPal button error:', err);
                                self.showMessage(`PayPal encountered an error: ${err.message}`);
                            }
                        }).render("#paypal-button-container");
                    } catch (error) {
                        console.error('PayPal button initialization error:', error);
                        self.showMessage(`Failed to initialize PayPal button: ${error.message}`);
                    }
                },

                showMessage(message) {
                    const container = document.querySelector("#result-message");
                    container.innerHTML = message;
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>

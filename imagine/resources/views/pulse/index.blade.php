<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">Purchase Pulse</h2>
                    
                    <!-- Pulse Amount Selection -->
                    <div class="mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($pulseAmounts as $option)
                            <div class="border rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition-colors"
                                onclick="selectPulseAmount({{ $option['amount'] }}, {{ $option['price'] }})">
                                <div class="text-3xl font-bold text-blue-600 mb-2">{{ number_format($option['amount']) }}</div>
                                <div class="text-gray-600 mb-4">Pulse</div>
                                <div class="text-2xl font-semibold">${{ number_format($option['price'], 2) }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Selected Amount Display -->
                    <div id="selected-amount" class="hidden mb-8 text-center">
                        <div class="text-lg text-gray-600">Selected Amount:</div>
                        <div class="text-2xl font-bold text-blue-600">
                            <span id="display-amount">0</span> Pulse for $<span id="display-price">0</span>
                        </div>
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
    <script src="https://www.paypal.com/sdk/js?client-id=test&currency=USD"></script>
    <script>
        let selectedAmount = 0;
        let selectedPrice = 0;

        function selectPulseAmount(amount, price) {
            selectedAmount = amount;
            selectedPrice = price;
            document.getElementById('selected-amount').classList.remove('hidden');
            document.getElementById('display-amount').textContent = amount.toLocaleString();
            document.getElementById('display-price').textContent = price.toFixed(2);
            
            // Clear and re-render PayPal buttons
            document.getElementById('paypal-button-container').innerHTML = '';
            initializePayPalButton();
        }

        function initializePayPalButton() {
            if (!selectedAmount || !selectedPrice) return;

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
                            },
                            body: JSON.stringify({
                                cart: [
                                    {
                                        id: `pulse_${selectedAmount}`,
                                        quantity: 1,
                                    },
                                ],
                            }),
                        });

                        const orderData = await response.json();

                        if (orderData.id) {
                            return orderData.id;
                        } else {
                            const errorDetail = orderData?.details?.[0];
                            const errorMessage = errorDetail
                                ? `${errorDetail.issue} ${errorDetail.description} (${orderData.debug_id})`
                                : JSON.stringify(orderData);

                            throw new Error(errorMessage);
                        }
                    } catch (error) {
                        console.error(error);
                        resultMessage(`Could not initiate PayPal Checkout...<br><br>${error}`);
                    }
                },
                async onApprove(data, actions) {
                    try {
                        const response = await fetch(`/api/orders/${data.orderID}/capture`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                        });

                        const orderData = await response.json();
                        const errorDetail = orderData?.details?.[0];

                        if (errorDetail?.issue === "INSTRUMENT_DECLINED") {
                            return actions.restart();
                        } else if (errorDetail) {
                            throw new Error(`${errorDetail.description} (${orderData.debug_id})`);
                        } else if (!orderData.purchase_units) {
                            throw new Error(JSON.stringify(orderData));
                        } else {
                            const transaction =
                                orderData?.purchase_units?.[0]?.payments?.captures?.[0] ||
                                orderData?.purchase_units?.[0]?.payments?.authorizations?.[0];
                            resultMessage(
                                `Transaction ${transaction.status}: ${transaction.id}<br><br>Your Pulse has been added to your account!`,
                            );
                            // Refresh the page after a successful transaction
                            setTimeout(() => window.location.reload(), 3000);
                        }
                    } catch (error) {
                        console.error(error);
                        resultMessage(
                            `Sorry, your transaction could not be processed...<br><br>${error}`,
                        );
                    }
                },
            }).render("#paypal-button-container");
        }

        function resultMessage(message) {
            const container = document.querySelector("#result-message");
            container.innerHTML = message;
        }
    </script>
    @endpush
</x-app-layout>

// Export for module usage
export function initPayPalButtons(options) {
    initPayPal(options);
}

// Also expose globally for script tag usage
window.initPayPalButtons = function(options) {
    initPayPal(options);
};

function initPayPal(options) {
    const { clientId, amount, price, onSuccess, onError } = options;

    // Clean up any existing buttons
    const container = document.getElementById('paypal-button-container');
    if (!container) return;
    container.innerHTML = '';

    // Load PayPal SDK
    const script = document.createElement('script');
    script.src = `https://www.paypal.com/sdk/js?client-id=${clientId}&currency=USD`;
    script.async = true;

    script.onload = () => {
        const buttons = window.paypal.Buttons({
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
                                id: `pulse_${amount}`,
                                quantity: 1,
                                price: price
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
                    onError?.(`Failed to create order: ${error.message}`);
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
                            amount: amount
                        })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Failed to capture payment');
                    }

                    const result = await response.json();
                    if (result.status === 'COMPLETED') {
                        onSuccess?.('Payment successful! Your Pulse has been added.');
                    } else {
                        throw new Error(`Payment not completed: ${result.status}`);
                    }
                } catch (error) {
                    console.error('Capture error:', error);
                    onError?.(`Payment failed: ${error.message}`);
                    throw error;
                }
            },
            onError: (err) => {
                console.error('PayPal error:', err);
                onError?.(`Payment error: ${err.message}`);
            },
            onCancel: () => {
                onError?.('Payment cancelled. Please try again.');
            }
        });

        if (buttons.isEligible && !buttons.isEligible()) {
            onError?.('PayPal Buttons are not eligible');
            return;
        }

        buttons.render('#paypal-button-container').catch(error => {
            console.error('PayPal button render error:', error);
            onError?.(`Failed to render PayPal button: ${error.message}`);
        });
    };

    script.onerror = () => {
        onError?.('Failed to load PayPal SDK');
    };

    document.body.appendChild(script);
}

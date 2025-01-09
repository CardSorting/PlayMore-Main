import { loadStripe } from '@stripe/stripe-js';

class StripeService {
    constructor() {
        this.stripe = null;
        this.elements = null;
    }

    async initialize(publishableKey) {
        if (!this.stripe) {
            this.stripe = await loadStripe(publishableKey);
        }
        return this.stripe;
    }

    async createPaymentIntent(cart) {
        try {
            const response = await fetch('/api/payment-intent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ cart })
            });

            if (!response.ok) {
                throw new Error('Failed to create payment intent');
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error creating payment intent:', error);
            throw error;
        }
    }

    async confirmPayment(paymentIntentId, amount) {
        try {
            const response = await fetch(`/api/payment-intent/${paymentIntentId}/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ amount })
            });

            if (!response.ok) {
                throw new Error('Failed to confirm payment');
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error confirming payment:', error);
            throw error;
        }
    }

    async createPaymentElement(clientSecret) {
        if (!this.stripe) {
            throw new Error('Stripe not initialized');
        }

        const elements = this.stripe.elements({
            clientSecret,
            appearance: {
                theme: 'stripe',
                variables: {
                    colorPrimary: '#0d6efd',
                    colorBackground: '#ffffff',
                    colorText: '#30313d',
                    colorDanger: '#df1b41',
                    fontFamily: 'system-ui, -apple-system, "Segoe UI", Roboto, sans-serif',
                    spacingUnit: '4px',
                    borderRadius: '4px'
                }
            }
        });

        const paymentElement = elements.create('payment');
        return { elements, paymentElement };
    }
}

export default new StripeService();

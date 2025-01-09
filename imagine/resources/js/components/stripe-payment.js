import stripeService from '../services/stripe-service';

export class StripePayment {
    constructor(container, options) {
        this.container = container;
        this.amount = options.amount;
        this.price = options.price;
        this.stripeKey = options.stripeKey;
        this.paymentElement = null;
        this.elements = null;
        this.stripe = null;
        this.form = null;
        this.submitButton = null;
        this.errorElement = null;
        
        this.initialize();
    }

    async initialize() {
        try {
            // Initialize Stripe
            this.stripe = await stripeService.initialize(this.stripeKey);
            
            // Create container elements
            this.createFormElements();
            
            // Create payment intent
            const { clientSecret } = await stripeService.createPaymentIntent([{
                id: 'credits',
                quantity: this.amount,
                price: this.price
            }]);

            // Create and mount payment element
            const { elements, paymentElement } = await stripeService.createPaymentElement(clientSecret);
            this.elements = elements;
            this.paymentElement = paymentElement;
            
            // Mount payment element
            this.paymentElement.mount('#payment-element');
            
            // Setup form submission
            this.setupFormSubmission();
        } catch (error) {
            console.error('Failed to initialize Stripe payment:', error);
            this.showError('Failed to initialize payment form. Please try again.');
        }
    }

    createFormElements() {
        // Create form container
        this.form = document.createElement('form');
        this.form.id = 'payment-form';
        this.form.className = 'space-y-4';

        // Create payment element container
        const paymentContainer = document.createElement('div');
        paymentContainer.id = 'payment-element';
        this.form.appendChild(paymentContainer);

        // Create error message container
        this.errorElement = document.createElement('div');
        this.errorElement.id = 'payment-error';
        this.errorElement.className = 'text-red-600 text-sm hidden';
        this.form.appendChild(this.errorElement);

        // Create submit button
        this.submitButton = document.createElement('button');
        this.submitButton.type = 'submit';
        this.submitButton.className = 'w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50';
        this.submitButton.textContent = `Pay $${this.price}`;
        this.form.appendChild(this.submitButton);

        // Add form to container
        this.container.appendChild(this.form);
    }

    setupFormSubmission() {
        this.form.addEventListener('submit', async (event) => {
            event.preventDefault();
            
            this.setLoading(true);
            this.hideError();

            try {
                const { error, paymentIntent } = await this.stripe.confirmPayment({
                    elements: this.elements,
                    confirmParams: {
                        return_url: window.location.href,
                    },
                    redirect: 'if_required'
                });

                if (error) {
                    this.showError(error.message);
                    return;
                }

                if (paymentIntent.status === 'succeeded') {
                    const result = await stripeService.confirmPayment(
                        paymentIntent.id,
                        this.amount
                    );

                    if (result.status === 'COMPLETED') {
                        window.location.reload();
                    } else {
                        this.showError('Payment completed but credits could not be added. Please contact support.');
                    }
                }
            } catch (error) {
                console.error('Payment failed:', error);
                this.showError('Payment failed. Please try again.');
            } finally {
                this.setLoading(false);
            }
        });
    }

    showError(message) {
        this.errorElement.textContent = message;
        this.errorElement.classList.remove('hidden');
    }

    hideError() {
        this.errorElement.textContent = '';
        this.errorElement.classList.add('hidden');
    }

    setLoading(isLoading) {
        this.submitButton.disabled = isLoading;
        this.submitButton.textContent = isLoading ? 'Processing...' : `Pay $${this.price}`;
    }
}

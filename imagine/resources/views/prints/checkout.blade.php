<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <x-prints.progress-stepper :currentStep="3" />
    </div>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <x-prints.order-summary :order="$printOrder" :maxQuantity="250" />
                </div>

                <!-- Payment Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <form id="payment-form" action="{{ route('prints.process-payment', $printOrder) }}" method="POST">
                            @csrf
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Details</h3>
                                <div id="payment-element" class="bg-gray-50 rounded-md p-4"></div>
                            </div>

                            <div>
                                <button type="submit" id="submit-button" 
                                        class="w-full flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span id="button-text">Pay ${{ $printOrder->formatted_total_price }}</span>
                                    <div id="spinner" class="hidden">
                                        <svg class="animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </button>
                            </div>

                            <div id="payment-message" class="hidden mt-4 p-4 rounded-md"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const clientSecret = '{{ $clientSecret }}';
    const paymentMessage = document.getElementById('payment-message');

    let elements;
    let paymentElement;
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('button-text');

    async function setupStripe() {
        elements = stripe.elements({ 
            clientSecret,
            appearance: {
                theme: 'stripe',
                variables: {
                    colorPrimary: '#2563eb',
                    colorBackground: '#f9fafb',
                    colorText: '#111827',
                    colorDanger: '#dc2626',
                    fontFamily: 'ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
                    spacingUnit: '4px',
                    borderRadius: '6px',
                }
            }
        });

        paymentElement = elements.create('payment', {
            layout: {
                type: 'tabs',
                defaultCollapsed: false,
            }
        });
        
        await paymentElement.mount('#payment-element');
    }

    async function handleSubmit(e) {
        e.preventDefault();
        setLoading(true);

        try {
            const { error } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: '{{ route('prints.success', $printOrder) }}',
                },
            });

            if (error) {
                showMessage(error.message, 'error');
            }
        } catch (e) {
            showMessage("An unexpected error occurred.", 'error');
        }

        setLoading(false);
    }

    function showMessage(messageText, type = 'error') {
        const messageElement = document.getElementById('payment-message');
        const bgColor = type === 'error' ? 'bg-red-50' : 'bg-green-50';
        const textColor = type === 'error' ? 'text-red-800' : 'text-green-800';
        const borderColor = type === 'error' ? 'border-red-200' : 'border-green-200';
        
        messageElement.className = `mt-4 p-4 rounded-md border ${bgColor} ${textColor} ${borderColor} text-sm`;
        messageElement.textContent = messageText;
        messageElement.classList.remove('hidden');
        
        setTimeout(() => {
            messageElement.classList.add('hidden');
        }, 6000);
    }

    function setLoading(isLoading) {
        submitButton.disabled = isLoading;
        spinner.classList.toggle('hidden', !isLoading);
        buttonText.classList.toggle('hidden', isLoading);
    }

    setupStripe().catch(error => {
        showMessage("Failed to load payment form. Please refresh the page.", 'error');
    });

    form.addEventListener('submit', handleSubmit);
</script>
@endpush
</x-app-layout>

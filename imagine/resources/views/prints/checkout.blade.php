<x-app-layout>
    <!-- Progress Bar -->
    <div class="border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex justify-center" aria-label="Progress">
                <ol role="list" class="flex items-center space-x-16 py-4">
                    <li class="flex items-center text-green-600">
                        <span class="relative flex h-8 w-8 items-center justify-center rounded-full bg-green-600">
                            <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="ml-3 text-sm font-medium">Details</span>
                    </li>
                    <li class="flex items-center text-blue-600">
                        <span class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-blue-600 bg-white">
                            <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                        </span>
                        <span class="ml-3 text-sm font-medium">Payment</span>
                    </li>
                    <li class="flex items-center text-gray-400">
                        <span class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white">
                            <span class="text-sm">3</span>
                        </span>
                        <span class="ml-3 text-sm font-medium">Confirmation</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-medium text-gray-900">Order Summary</h2>
                            <img src="{{ $printOrder->gallery->image_url }}" alt="{{ $printOrder->gallery->prompt }}" class="w-24 h-24 object-cover rounded-lg shadow">
                        </div>
                        <dl class="divide-y divide-gray-200">
                            <div class="py-4 flex justify-between">
                                <dt class="text-sm text-gray-600">Print Size</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ \App\Models\PrintOrder::getSizeName($printOrder->size) }}</dd>
                            </div>
                            <div class="py-4">
                                <dt class="text-sm text-gray-600 mb-1">Shipping To</dt>
                                <dd class="text-sm text-gray-900">
                                    <address class="not-italic">
                                        {{ $printOrder->shipping_name }}<br>
                                        {{ $printOrder->shipping_address }}<br>
                                        {{ $printOrder->shipping_city }}, {{ $printOrder->shipping_state }} {{ $printOrder->shipping_zip }}<br>
                                        {{ $printOrder->shipping_country }}
                                    </address>
                                </dd>
                            </div>
                            <div class="py-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900">${{ number_format($printOrder->price, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-2">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium text-gray-900">Free</span>
                                </div>
                                <div class="flex justify-between text-base font-medium mt-4 pt-4 border-t border-gray-200">
                                    <span class="text-gray-900">Total</span>
                                    <span class="text-gray-900">${{ number_format($printOrder->price, 2) }}</span>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <form id="payment-form" action="{{ route('prints.confirm', $printOrder) }}" method="POST">
                            @csrf
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Details</h3>
                                <div id="payment-element" class="bg-gray-50 rounded-md p-4"></div>
                            </div>

                            <div>
                                <button type="submit" id="submit-button" 
                                        class="w-full flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span id="button-text">Pay ${{ number_format($printOrder->price, 2) }}</span>
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

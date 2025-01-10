<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Message -->
            <div class="text-center mb-12">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-3">Order Confirmed!</h1>
                <p class="text-lg text-gray-600">Thank you for your order. We'll start processing it right away.</p>
            </div>

            <!-- Order Details Card -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Order Details</h2>
                    <p class="text-sm text-gray-600">Order #{{ $printOrder->id }}</p>
                </div>

                <div class="px-6 py-4">
                    <div class="flex items-center mb-6">
                        <div class="flex-shrink-0">
                            <img src="{{ $printOrder->gallery->image_url }}" alt="{{ $printOrder->gallery->prompt }}" class="h-24 w-24 object-cover rounded-lg">
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $printOrder->gallery->prompt }}</h3>
                            <div class="mt-1 text-sm text-gray-600">
                                <p>Size: {{ $printOrder->getSizeNameAttribute() }}</p>
                                <p>Material: {{ $printOrder->getMaterialNameAttribute() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Shipping Address</h4>
                        <address class="text-sm text-gray-600 not-italic">
                            {{ $printOrder->shipping_name }}<br>
                            {{ $printOrder->shipping_address }}<br>
                            {{ $printOrder->shipping_city }}, {{ $printOrder->shipping_state }} {{ $printOrder->shipping_zip }}<br>
                            {{ $printOrder->shipping_country }}
                        </address>
                    </div>

                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900">${{ number_format($printOrder->price / 100, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-gray-900">Free</span>
                        </div>
                        <div class="flex justify-between text-base font-medium mt-4 pt-4 border-t border-gray-200">
                            <span class="text-gray-900">Total</span>
                            <span class="text-gray-900">${{ number_format($printOrder->price / 100, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4">
                    <div class="text-sm">
                        <h4 class="font-medium text-gray-900 mb-2">What's Next?</h4>
                        <ul class="text-gray-600 space-y-2">
                            <li>• We'll send you an email confirmation with your order details</li>
                            <li>• Your print will be carefully produced and quality checked</li>
                            <li>• Estimated delivery: {{ $printOrder->getEstimatedDeliveryDateAttribute() }}</li>
                            <li>• You can track your order status in your <a href="{{ route('prints.index') }}" class="text-blue-600 hover:text-blue-500">order history</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-center space-x-4">
                <a href="{{ route('prints.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    View Order History
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Return to Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

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
                    <li class="flex items-center text-green-600">
                        <span class="relative flex h-8 w-8 items-center justify-center rounded-full bg-green-600">
                            <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="ml-3 text-sm font-medium">Payment</span>
                    </li>
                    <li class="flex items-center text-green-600">
                        <span class="relative flex h-8 w-8 items-center justify-center rounded-full bg-green-600">
                            <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="ml-3 text-sm font-medium">Confirmation</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Message -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Thank You!</h2>
                <p class="text-lg text-gray-600">Your order has been confirmed and will be processed shortly.</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Order Header -->
                <div class="border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Order #{{ $printOrder->id }}</h3>
                            <p class="mt-1 text-sm text-gray-500">Placed on {{ $printOrder->created_at->format('F j, Y') }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $printOrder->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($printOrder->status) }}
                        </span>
                    </div>
                </div>

                <!-- Order Content -->
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Details -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Product Details</h4>
                            <div class="flex items-start space-x-4">
                                <img src="{{ $printOrder->gallery->image_url }}" alt="{{ $printOrder->gallery->prompt }}" class="w-24 h-24 object-cover rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Custom Print</p>
                                    <p class="mt-1 text-sm text-gray-500">Size: {{ \App\Models\PrintOrder::getSizeName($printOrder->size) }}</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">${{ number_format($printOrder->price, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Shipping Information</h4>
                            <address class="not-italic text-sm text-gray-500">
                                <p class="font-medium text-gray-900">{{ $printOrder->shipping_name }}</p>
                                <p>{{ $printOrder->shipping_address }}</p>
                                <p>{{ $printOrder->shipping_city }}, {{ $printOrder->shipping_state }} {{ $printOrder->shipping_zip }}</p>
                                <p>{{ $printOrder->shipping_country }}</p>
                            </address>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-gray-50 px-6 py-4">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-900">Order Total</span>
                        <span class="font-medium text-gray-900">${{ number_format($printOrder->price, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-between items-center">
                <a href="{{ route('prints.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    View All Orders
                </a>
                <a href="{{ route('images.gallery') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Continue Shopping
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
</x-app-layout>

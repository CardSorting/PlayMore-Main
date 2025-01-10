<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Navigation -->
            <div class="mb-6">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Orders
                </a>
            </div>

            <!-- Order Details Card -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <!-- Order Header -->
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center space-x-3">
                                <h1 class="text-xl font-semibold text-gray-900">Order #{{ $order->id }}</h1>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                       ($order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : 
                                       'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Placed on {{ $order->created_at->format('F j, Y g:i A') }}
                            </p>
                        </div>
                        <div>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Cancel Order
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Order Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column: Print Details and Payment -->
                        <div class="space-y-6">
                            <!-- Print Details -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Print Details</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-start space-x-4">
                                        <img src="{{ $order->gallery->image_url }}" alt="Print preview" class="h-32 w-32 object-cover rounded-lg border border-gray-200">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ \App\Models\PrintOrder::getSizeName($order->size) }} Print</h4>
                                            <p class="mt-1 text-sm text-gray-500">{{ $order->gallery->prompt }}</p>
                                            <div class="mt-4">
                                                <button type="button" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-500">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                    Download Preview
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dl class="divide-y divide-gray-200">
                                        <div class="py-3 flex justify-between">
                                            <dt class="text-sm text-gray-600">Payment Method</dt>
                                            <dd class="text-sm font-medium text-gray-900">•••• 4242</dd>
                                        </div>
                                        <div class="py-3 flex justify-between">
                                            <dt class="text-sm text-gray-600">Transaction ID</dt>
                                            <dd class="text-sm font-medium text-gray-900">{{ substr($order->stripe_payment_intent_id, -8) }}</dd>
                                        </div>
                                        <div class="py-3 flex justify-between">
                                            <dt class="text-sm text-gray-600">Amount Paid</dt>
                                            <dd class="text-sm font-medium text-gray-900">${{ number_format($order->price, 2) }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Shipping and Timeline -->
                        <div class="space-y-6">
                            <!-- Shipping Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Information</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <address class="not-italic text-sm text-gray-600">
                                            <p class="font-medium text-gray-900">{{ $order->shipping_name }}</p>
                                            <p>{{ $order->shipping_address }}</p>
                                            <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                                            <p>{{ $order->shipping_country }}</p>
                                        </address>
                                        <button type="button" class="text-sm text-blue-600 hover:text-blue-500">
                                            Edit
                                        </button>
                                    </div>
                                    @if($order->status !== 'completed')
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <div class="text-sm text-gray-600">
                                                    Estimated Delivery
                                                </div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ now()->addDays(5)->format('F j, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Order Timeline -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Timeline</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flow-root">
                                        <ul role="list" class="-mb-8">
                                            <li>
                                                <div class="relative pb-8">
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                    <div class="relative flex items-start space-x-3">
                                                        <div class="relative">
                                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div class="text-sm font-medium text-gray-900">Order Placed</div>
                                                            <div class="mt-0.5 text-sm text-gray-500">
                                                                {{ $order->created_at->format('M j, Y g:i A') }}
                                                            </div>
                                                            <div class="mt-2 text-sm text-gray-500">
                                                                Order confirmed and payment processed
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            @if($order->status !== 'pending')
                                                <li>
                                                    <div class="relative pb-8">
                                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                        <div class="relative flex items-start space-x-3">
                                                            <div class="relative">
                                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                            <div class="min-w-0 flex-1">
                                                                <div class="text-sm font-medium text-gray-900">Processing Started</div>
                                                                <div class="mt-0.5 text-sm text-gray-500">
                                                                    {{ $order->updated_at->format('M j, Y g:i A') }}
                                                                </div>
                                                                <div class="mt-2 text-sm text-gray-500">
                                                                    Print production in progress
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif

                                            @if($order->status === 'completed')
                                                <li>
                                                    <div class="relative">
                                                        <div class="relative flex items-start space-x-3">
                                                            <div class="relative">
                                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                            <div class="min-w-0 flex-1">
                                                                <div class="text-sm font-medium text-gray-900">Order Completed</div>
                                                                <div class="mt-0.5 text-sm text-gray-500">
                                                                    {{ $order->updated_at->format('M j, Y g:i A') }}
                                                                </div>
                                                                <div class="mt-2 text-sm text-gray-500">
                                                                    Print delivered to shipping address
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-3">
                            <button type="button" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                Download Invoice
                            </button>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Contact Support
                            </button>
                        </div>
                        @if($order->status === 'completed')
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Buy Again
                            </button>
                        @endif
                    </div>
                </div>
        </div>
    </div>
</div>
</x-app-layout>

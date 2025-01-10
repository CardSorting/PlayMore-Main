<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between mb-8">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Print Orders</h1>
                    <p class="mt-2 text-sm text-gray-600">Track and manage your print orders</p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('images.gallery') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Order New Print
                    </a>
                </div>
            </div>

            @if($orders->isEmpty())
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-blue-100 mb-6">
                        <svg class="h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No Print Orders Yet</h3>
                    <p class="text-gray-500 mb-6 max-w-sm mx-auto">Start by ordering prints of your favorite AI-generated creations.</p>
                    <a href="{{ route('images.gallery') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Browse Gallery
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            @else
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <li class="p-6 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex items-center justify-between flex-wrap lg:flex-nowrap">
                                    <!-- Order Image and Basic Info -->
                                    <div class="flex items-center flex-1 min-w-0">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $order->gallery->image_url }}" alt="{{ $order->gallery->prompt }}" class="w-20 h-20 object-cover rounded-lg shadow-sm">
                                        </div>
                                        <div class="ml-4">
                                            <div class="flex items-center space-x-2">
                                                <h3 class="text-sm font-medium text-gray-900">Order #{{ $order->id }}</h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                       ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                                       'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </div>
                                            <div class="mt-1">
                                                <p class="text-sm text-gray-500">Size: {{ \App\Models\PrintOrder::getSizeName($order->size) }}</p>
                                                <p class="text-sm text-gray-500">Ordered: {{ $order->created_at->format('M j, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price and Actions -->
                                    <div class="mt-4 lg:mt-0 flex items-center space-x-4">
                                        <span class="text-sm font-medium text-gray-900">${{ number_format($order->price, 2) }}</span>
                                        <button type="button" onclick="toggleDetails('order-{{ $order->id }}')"
                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <span id="button-text-{{ $order->id }}">View Details</span>
                                            <svg id="button-icon-{{ $order->id }}" class="ml-1.5 w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Order Details (Hidden by default) -->
                                <div id="order-{{ $order->id }}" class="hidden mt-6 pt-6 border-t border-gray-200">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Shipping Information</h4>
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <address class="not-italic text-sm text-gray-600 space-y-1">
                                                    <p class="font-medium text-gray-900">{{ $order->shipping_name }}</p>
                                                    <p>{{ $order->shipping_address }}</p>
                                                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                                                    <p>{{ $order->shipping_country }}</p>
                                                </address>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Order Timeline</h4>
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <div class="space-y-3">
                                                    <div class="flex items-center text-sm">
                                                        <div class="flex-shrink-0">
                                                            <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-gray-600">Order Placed</p>
                                                            <p class="text-gray-500">{{ $order->created_at->format('M j, Y g:i A') }}</p>
                                                        </div>
                                                    </div>
                                                    @if($order->status === 'processing' || $order->status === 'completed')
                                                        <div class="flex items-center text-sm">
                                                            <div class="flex-shrink-0">
                                                                <svg class="h-5 w-5 {{ $order->status === 'completed' ? 'text-green-500' : 'text-blue-500' }}" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <div class="ml-3">
                                                                <p class="text-gray-600">Processing Started</p>
                                                                <p class="text-gray-500">{{ $order->updated_at->format('M j, Y g:i A') }}</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($order->status === 'completed')
                                                        <div class="flex items-center text-sm">
                                                            <div class="flex-shrink-0">
                                                                <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <div class="ml-3">
                                                                <p class="text-gray-600">Order Completed</p>
                                                                <p class="text-gray-500">{{ $order->updated_at->format('M j, Y g:i A') }}</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @endif
            @endif
    </div>
</div>

@push('scripts')
<script>
function toggleDetails(id) {
    const details = document.getElementById(id);
    const buttonText = document.getElementById(`button-text-${id.replace('order-', '')}`);
    const buttonIcon = document.getElementById(`button-icon-${id.replace('order-', '')}`);
    
    if (details.classList.contains('hidden')) {
        details.classList.remove('hidden');
        buttonText.textContent = 'Hide Details';
        buttonIcon.style.transform = 'rotate(180deg)';
    } else {
        details.classList.add('hidden');
        buttonText.textContent = 'View Details';
        buttonIcon.style.transform = 'rotate(0)';
    }
}
</script>
@endpush
</x-app-layout>

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.prints.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Orders
                </a>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <!-- Order Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-semibold text-gray-900">Order #{{ $order->id }}</h1>
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                               ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                               ($order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : 
                               'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        Ordered {{ $order->created_at->format('F j, Y g:i A') }}
                    </p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Order Details -->
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Order Details</h2>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-4">
                                    <img src="{{ $order->gallery->image_url }}" alt="Print preview" class="h-24 w-24 rounded-lg object-cover">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Print Size</div>
                                        <div class="text-sm text-gray-500">{{ \App\Models\PrintOrder::getSizeName($order->size) }}</div>
                                        <div class="mt-2 text-sm font-medium text-gray-900">Price</div>
                                        <div class="text-sm text-gray-500">${{ number_format($order->price, 2) }}</div>
                                    </div>
                                </div>
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="text-sm font-medium text-gray-900">Payment ID</div>
                                    <div class="text-sm text-gray-500">{{ $order->stripe_payment_intent_id }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer & Shipping -->
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h2>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="mb-4">
                                    <div class="text-sm font-medium text-gray-900">Customer</div>
                                    <div class="text-sm text-gray-500">{{ $order->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                </div>
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="text-sm font-medium text-gray-900">Shipping Address</div>
                                    <div class="text-sm text-gray-500">{{ $order->shipping_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->shipping_address }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $order->shipping_country }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Update -->
                    <div class="mt-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Update Status</h2>
                        <form action="{{ route('admin.prints.update-status', $order) }}" method="POST" class="flex items-center space-x-4">
                            @csrf
                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Status
                            </button>
                        </form>
                    </div>

                    <!-- Timeline -->
                    <div class="mt-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Order Timeline</h2>
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Order placed</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $order->created_at->format('M j, Y g:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @if($order->status !== 'pending')
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Processing started</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $order->updated_at->format('M j, Y g:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                @if($order->status === 'completed')
                                    <li>
                                        <div class="relative">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Order completed</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $order->updated_at->format('M j, Y g:i A') }}
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
</div>
@endsection

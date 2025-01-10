@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">Print Orders</h1>
            
            <!-- Stats -->
            <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total Orders</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $stats['total'] }}</dd>
                </div>
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Revenue</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">${{ number_format($stats['revenue'], 2) }}</dd>
                </div>
                <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Pending Orders</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $stats['pending'] }}</dd>
                </div>
            </dl>

            <!-- Filters -->
            <div class="mt-8 bg-white shadow rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.prints.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Order ID, customer name..."
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.prints.export', request()->query()) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Export CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="mt-8 bg-white shadow rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.prints.bulk-update-status') }}" method="POST">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Order Details
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Shipping
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="orders[]" value="{{ $order->id }}" class="order-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <img src="{{ $order->gallery->image_url }}" alt="Print preview" class="h-10 w-10 rounded-lg object-cover">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            Order #{{ $order->id }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ \App\Models\PrintOrder::getSizeName($order->size) }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            ${{ number_format($order->price, 2) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ $order->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ $order->shipping_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $order->shipping_address }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                       ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                                       ($order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : 
                                                       'bg-gray-100 text-gray-800')) }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <a href="{{ route('admin.prints.show', $order) }}" class="text-blue-600 hover:text-blue-900">View Details</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No orders found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Bulk Actions -->
                        <div class="mt-4 flex items-center space-x-4">
                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Bulk Update Status</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="completed">Completed</option>
                            </select>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Selected
                            </button>
                        </div>
                    </form>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('select-all').addEventListener('change', function() {
    document.querySelectorAll('.order-checkbox').forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});
</script>
@endpush

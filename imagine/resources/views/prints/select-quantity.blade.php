@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Select Quantity</h2>
                
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $order->gallery->prompt }}</h3>
                            <p class="text-sm text-gray-500">{{ $order->size }} - {{ ucfirst($order->material) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Unit Price</p>
                            <p class="text-lg font-medium text-gray-900">${{ number_format($order->unit_price / 100, 2) }}</p>
                        </div>
                    </div>

                    <form action="{{ route('prints.update-quantity', $order) }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                            <div class="mt-2 flex items-center">
                                <select name="quantity" id="quantity" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @for ($i = 1; $i <= $maxQuantity; $i++)
                                        <option value="{{ $i }}" {{ $order->quantity == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            @error('quantity')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <div>
                                <p class="text-sm text-gray-500">Total Price</p>
                                <p class="text-2xl font-bold text-gray-900" x-text="'$' + (quantity * {{ $order->unit_price }} / 100).toFixed(2)">
                                    ${{ number_format($order->total_price / 100, 2) }}
                                </p>
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Continue to Checkout
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('quantity').addEventListener('change', function() {
        const quantity = parseInt(this.value);
        const unitPrice = {{ $order->unit_price }};
        const total = (quantity * unitPrice / 100).toFixed(2);
        document.querySelector('[x-text]').textContent = '$' + total;
    });
</script>
@endpush

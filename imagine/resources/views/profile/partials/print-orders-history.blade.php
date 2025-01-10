<section>
    <!-- Amazon-style filter bar with expanded options -->
    <div class="bg-white border border-gray-200 rounded-sm mb-4">
        <div class="p-4">
            <form action="{{ route('profile.edit') }}" method="GET">
                <div class="sm:flex sm:items-center sm:justify-between mb-4">
                    <div class="flex flex-wrap gap-4">
                        <select name="period" class="rounded border-gray-300 text-sm focus:border-[#febd69] focus:ring-[#febd69] min-w-[140px]" onchange="this.form.submit()">
                            <option value="">Orders placed in...</option>
                            <option value="last30days" {{ request('period') === 'last30days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="past3months" {{ request('period') === 'past3months' ? 'selected' : '' }}>Past 3 Months</option>
                            <option value="2024" {{ request('period') === '2024' ? 'selected' : '' }}>2024</option>
                            <option value="2023" {{ request('period') === '2023' ? 'selected' : '' }}>2023</option>
                        </select>
                        <div class="relative flex-1 min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search all orders" 
                                   class="w-full pl-10 pr-4 py-2 rounded border-gray-300 text-sm focus:border-[#febd69] focus:ring-[#febd69]">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <button type="submit" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @if(request('period') || request('search') || request('status'))
                        <a href="{{ route('profile.edit') }}" class="mt-3 sm:mt-0 text-sm text-[#007185] hover:text-[#c7511f] hover:underline">
                            Clear filters
                        </a>
                    @endif
                </div>
                <!-- Enhanced Filter Options -->
                <div class="flex flex-wrap gap-4 text-sm border-t border-gray-200 pt-4 mt-4">
                    <span class="font-medium text-[#0F1111] self-center">Filter by:</span>
                    <div class="flex flex-wrap gap-3">
                        <label class="relative inline-flex items-center group">
                            <input type="radio" name="status" value="" class="absolute opacity-0 w-0 h-0" {{ !request('status') ? 'checked' : '' }} onchange="this.form.submit()">
                            <span class="cursor-pointer py-1 px-3 rounded {{ !request('status') ? 'bg-[#F0F2F2] font-medium' : 'hover:bg-gray-100' }}">
                                All orders ({{ $orderCounts['total'] }})
                            </span>
                        </label>
                        <label class="relative inline-flex items-center group">
                            <input type="radio" name="status" value="pending" class="absolute opacity-0 w-0 h-0" {{ request('status') === 'pending' ? 'checked' : '' }} onchange="this.form.submit()">
                            <span class="cursor-pointer py-1 px-3 rounded {{ request('status') === 'pending' ? 'bg-[#F0F2F2] font-medium' : 'hover:bg-gray-100' }}">
                                Not yet shipped ({{ $orderCounts['pending'] }})
                            </span>
                        </label>
                        <label class="relative inline-flex items-center group">
                            <input type="radio" name="status" value="shipped" class="absolute opacity-0 w-0 h-0" {{ request('status') === 'shipped' ? 'checked' : '' }} onchange="this.form.submit()">
                            <span class="cursor-pointer py-1 px-3 rounded {{ request('status') === 'shipped' ? 'bg-[#F0F2F2] font-medium' : 'hover:bg-gray-100' }}">
                                In transit ({{ $orderCounts['shipped'] }})
                            </span>
                        </label>
                        <label class="relative inline-flex items-center group">
                            <input type="radio" name="status" value="completed" class="absolute opacity-0 w-0 h-0" {{ request('status') === 'completed' ? 'checked' : '' }} onchange="this.form.submit()">
                            <span class="cursor-pointer py-1 px-3 rounded {{ request('status') === 'completed' ? 'bg-[#F0F2F2] font-medium' : 'hover:bg-gray-100' }}">
                                Delivered ({{ $orderCounts['completed'] }})
                            </span>
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($printOrders->isEmpty())
        <div class="bg-white p-6 text-center rounded-sm border border-gray-200">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No orders placed</h3>
            <p class="text-sm text-gray-500">Looking to order prints? Check out your gallery to get started.</p>
            <a href="/dashboard/gallery" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-sm text-white bg-[#232f3e] hover:bg-[#374357] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#232f3e]">
                Browse Gallery
            </a>
        </div>
    @else
        @php
            $orders = $printOrders->groupBy(function($order) {
                return $order->created_at->format('F Y');
            });
        @endphp

        @foreach($orders as $month => $monthOrders)
            <div class="mb-8">
                <h3 class="text-lg font-medium text-[#232f3e] mb-4">{{ $month }}</h3>
                
                @foreach($monthOrders as $order)
                    <div class="bg-white border border-gray-200 rounded-sm mb-4">
                        <!-- Order Header -->
                        <div class="border-b border-gray-200 bg-gray-50 p-4">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div>
                                    <div class="text-xs text-gray-500">ORDER PLACED</div>
                                    <div class="text-sm">{{ $order->created_at->format('M j, Y') }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">TOTAL</div>
                                    <div class="text-sm">${{ number_format($order->price, 2) }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">SHIP TO</div>
                                    <div class="text-sm truncate">{{ $order->shipping_name }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-gray-500">ORDER # {{ $order->id }}</div>
                                    <a href="{{ route('profile.orders.show', $order) }}" class="text-sm text-[#007185] hover:text-[#c7511f] hover:underline">
                                        View order details
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Order Content -->
                        <div class="p-4">
                            <!-- Delivery Status with Progress Bar -->
                            <div class="mb-6">
                                <div class="flex items-baseline justify-between mb-2">
                                    <div>
                                        <span class="text-base font-medium {{ 
                                            $order->status === 'completed' ? 'text-green-700' : 
                                            ($order->status === 'processing' ? 'text-[#007185]' : 
                                            ($order->status === 'shipped' ? 'text-[#007185]' : 'text-[#007185]')) 
                                        }}">
                                            @if($order->status === 'completed')
                                                Delivered {{ $order->updated_at->format('F j') }}
                                            @elseif($order->status === 'shipped')
                                                Arriving today by 8PM
                                            @elseif($order->status === 'processing')
                                                Delivery estimate: {{ now()->addDays(3)->format('F j') }}
                                            @else
                                                Expected delivery: {{ now()->addDays(5)->format('F j') }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-sm">
                                        <button type="button" class="text-[#007185] hover:text-[#c7511f] hover:underline">
                                            Track package
                                        </button>
                                    </div>
                                </div>

                                <!-- Enhanced Progress Bar -->
                                <div class="relative">
                                    <div class="overflow-hidden h-2 text-xs flex bg-gray-100 rounded">
                                        <div class="flex-1 relative">
                                            <!-- Progress Steps with Improved Styling -->
                                            <div class="absolute inset-0 flex justify-between items-center px-1">
                                                <div class="w-4 h-4 rounded-full border-2 {{ 
                                                    $order->status !== 'pending' 
                                                        ? 'border-[#007185] bg-white' 
                                                        : 'border-gray-300 bg-gray-100' 
                                                }}"></div>
                                                <div class="w-4 h-4 rounded-full border-2 {{ 
                                                    in_array($order->status, ['processing', 'shipped', 'completed']) 
                                                        ? 'border-[#007185] bg-white' 
                                                        : 'border-gray-300 bg-gray-100' 
                                                }}"></div>
                                                <div class="w-4 h-4 rounded-full border-2 {{ 
                                                    in_array($order->status, ['shipped', 'completed']) 
                                                        ? 'border-[#007185] bg-white' 
                                                        : 'border-gray-300 bg-gray-100' 
                                                }}"></div>
                                                <div class="w-4 h-4 rounded-full border-2 {{ 
                                                    $order->status === 'completed' 
                                                        ? 'border-[#007185] bg-white' 
                                                        : 'border-gray-300 bg-gray-100' 
                                                }}"></div>
                                            </div>
                                            <!-- Progress Fill with Animation -->
                                            <div class="h-full transition-all duration-500 {{ 
                                                $order->status === 'completed' ? 'w-full bg-[#007185]' : 
                                                ($order->status === 'shipped' ? 'w-3/4 bg-[#007185]' : 
                                                ($order->status === 'processing' ? 'w-1/2 bg-[#007185]' : 
                                                'w-1/4 bg-[#007185]')) 
                                            }}"></div>
                                        </div>
                                    </div>
                                    <!-- Progress Labels with Status Details -->
                                    <div class="flex justify-between text-xs mt-2">
                                        <div class="text-center">
                                            <div class="{{ $order->status !== 'pending' ? 'text-[#007185] font-medium' : 'text-gray-500' }}">Ordered</div>
                                            <div class="text-[10px] text-gray-500">{{ $order->created_at->format('M j, g:i A') }}</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="{{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'text-[#007185] font-medium' : 'text-gray-500' }}">Processing</div>
                                            @if(in_array($order->status, ['processing', 'shipped', 'completed']))
                                                <div class="text-[10px] text-gray-500">{{ $order->created_at->addHours(2)->format('M j, g:i A') }}</div>
                                            @endif
                                        </div>
                                        <div class="text-center">
                                            <div class="{{ in_array($order->status, ['shipped', 'completed']) ? 'text-[#007185] font-medium' : 'text-gray-500' }}">Shipped</div>
                                            @if(in_array($order->status, ['shipped', 'completed']))
                                                <div class="text-[10px] text-gray-500">{{ $order->created_at->addDays(1)->format('M j') }}</div>
                                            @endif
                                        </div>
                                        <div class="text-center">
                                            <div class="{{ $order->status === 'completed' ? 'text-[#007185] font-medium' : 'text-gray-500' }}">Delivered</div>
                                            @if($order->status === 'completed')
                                                <div class="text-[10px] text-gray-500">{{ $order->updated_at->format('M j') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product and Actions -->
                            <div class="sm:flex sm:items-start sm:justify-between">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $order->gallery->image_url }}" alt="Print preview" class="h-24 w-24 object-cover rounded-sm border border-gray-200">
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-[#0F1111] hover:text-[#c7511f]">
                                            <a href="{{ route('profile.orders.show', $order) }}" class="hover:underline">
                                                {{ \App\Models\PrintOrder::getSizeName($order->size) }} Print
                                            </a>
                                        </h4>
                                        <div class="mt-1 text-sm text-gray-500">
                                            Shipping to: {{ $order->shipping_city }}, {{ $order->shipping_state }}
                                        </div>
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            @if($order->status === 'completed')
                                                <button type="button" class="text-sm text-[#007185] hover:text-[#c7511f] hover:underline">
                                                    Buy again
                                                </button>
                                            @endif
                                            <span class="text-gray-300">|</span>
                                            <a href="{{ route('profile.orders.show', $order) }}" class="text-sm text-[#007185] hover:text-[#c7511f] hover:underline">
                                                View order details
                                            </a>
                                            <span class="text-gray-300">|</span>
                                            <button type="button" class="text-sm text-[#007185] hover:text-[#c7511f] hover:underline">
                                                Problem with order
                                            </button>
                                            <span class="text-gray-300">|</span>
                                            <button type="button" class="text-sm text-[#007185] hover:text-[#c7511f] hover:underline">
                                                Archive order
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-6">
            {{ $printOrders->links() }}
        </div>
    @endif
</section>

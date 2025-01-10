@props(['title', 'description', 'nextStep', 'author', 'price', 'isAvailable' => true])

<div x-data="{ showStickyBar: false }" class="relative">
    <!-- Product Header -->
    <div class="border-b border-gray-200 pb-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $title }}</h1>
        
        <!-- Creation Info -->
        <div class="mt-4 flex items-center space-x-3">
            <!-- AI Badge -->
            <div class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1">
            <svg class="mr-1.5 h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
                <span class="text-sm font-medium text-blue-700">AI-Generated Masterpiece</span>
            </div>
            
            <!-- Author -->
            <div class="inline-flex items-center rounded-full bg-gray-50 px-3 py-1">
                <svg class="mr-1.5 h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium text-gray-700">Created by {{ $author }}</span>
            </div>
        </div>

        <!-- Description -->
        <div class="mt-6 space-y-6 text-base text-gray-500">
            <p>{{ $description }}</p>
        </div>

        <!-- Price and Status -->
        <div class="mt-6 flex flex-col space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-baseline">
                    <span class="text-3xl font-bold tracking-tight text-gray-900">${{ number_format($price / 100, 2) }}</span>
                    <span class="ml-2 text-sm text-gray-500">USD</span>
                </div>
                @if($isAvailable)
                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-0.5 text-sm font-medium text-green-800">
                        <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        In Stock
                    </span>
                @else
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-0.5 text-sm font-medium text-gray-800">
                        <svg class="mr-1.5 h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Sold Out
                    </span>
                @endif
            </div>

            <!-- Shipping Info -->
            <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                2-5 business days shipping
            </div>

            <!-- Quantity Form -->
            @if($isAvailable && $nextStep)
                <form action="{{ $nextStep }}" method="POST" class="mt-2">
                    @csrf
                    <div class="flex flex-col space-y-4">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                            <div class="mt-1 max-w-[120px]">
                                <input type="number" 
                                    name="quantity" 
                                    id="quantity"
                                    value="1"
                                    min="1"
                                    max="250"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required>
                            </div>
                        </div>
                        <button type="submit" 
                            class="w-full inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Start Customizing
                        </button>
                    </div>
                </form>
            @else
                <div class="mt-2">
                    <button type="button" 
                        disabled
                        class="w-full inline-flex items-center justify-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
                        Currently Unavailable
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Sticky Bar -->
    <div x-show="showStickyBar" 
         x-transition:enter="transform transition-transform duration-300"
         x-transition:enter-start="-translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transform transition-transform duration-300"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="-translate-y-full"
         class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 shadow-sm"
         @scroll.window="showStickyBar = window.pageYOffset > 400">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <h2 class="text-lg font-medium text-gray-900">{{ $title }}</h2>
                    <span class="ml-4 text-sm text-gray-500">${{ number_format($price / 100, 2) }}</span>
                </div>
                @if($isAvailable && $nextStep)
                    <button type="button" 
                        onclick="document.getElementById('quantity').focus()"
                        class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Select Quantity
                    </button>
                @else
                    <button type="button" 
                        disabled
                        class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
                        Currently Unavailable
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{ $slot }}
</div>

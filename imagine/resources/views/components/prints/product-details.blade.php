@props(['title', 'description', 'nextStep'])

<div x-data="{ showStickyBar: false }" class="relative">
    <!-- Product Header -->
    <div class="border-b border-gray-200 pb-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $title }}</h1>
        
        <!-- Price and Status -->
        <div class="mt-3 flex items-center space-x-4">
            <div class="flex items-baseline">
                <span class="text-3xl font-bold tracking-tight text-gray-900">From $19.99</span>
                <span class="ml-2 text-sm text-gray-500">USD</span>
            </div>
            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-0.5 text-sm font-medium text-green-800">
                <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3" />
                </svg>
                In Stock
            </span>
        </div>

        <!-- AI Badge -->
        <div class="mt-4 inline-flex items-center rounded-full bg-blue-50 px-3 py-1">
            <svg class="mr-1.5 h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
            <span class="text-sm font-medium text-blue-700">AI-Generated Masterpiece</span>
        </div>

        <!-- Description -->
        <div class="mt-6 space-y-6 text-base text-gray-500">
            <p>{{ $description }}</p>
        </div>

        <!-- Quantity Form -->
        <form action="{{ $nextStep }}" method="POST" class="mt-6">
            @csrf
            <div class="flex items-end gap-4">
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
                    class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Start Customizing
                </button>
            </div>
        </form>
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
                    <span class="ml-4 text-sm text-gray-500">From $19.99</span>
                </div>
                <button type="button" 
                    onclick="document.getElementById('quantity').focus()"
                    class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Select Quantity
                </button>
            </div>
        </div>
    </div>

    {{ $slot }}
</div>

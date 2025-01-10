@props(['title', 'description'])

<div x-data="{ showStickyBar: false }" class="relative">
    <!-- Sticky Product Summary Bar -->
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
                <a href="#customize" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                    Start Customizing
                </a>
            </div>
        </div>
    </div>

    <div class="space-y-8">
        <!-- Title and Description -->
        <div>
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $title }}</h1>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-0.5 text-sm font-medium text-green-800">
                        <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        In Stock
                    </span>
                    <div class="flex items-center">
                        <svg class="text-yellow-400 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="ml-1 text-sm text-gray-500">AI-Generated Masterpiece</span>
                    </div>
                </div>
            </div>
            <p class="mt-4 text-lg text-gray-500">{{ $description }}</p>
        </div>

        <!-- Trust Badges -->
        <div class="grid grid-cols-4 gap-4 border-t border-b border-gray-200 py-6">
            <div class="text-center">
                <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-2 text-xs font-medium text-gray-500">Fast Production</p>
            </div>
            <div class="text-center">
                <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                </svg>
                <p class="mt-2 text-xs font-medium text-gray-500">Quality Guarantee</p>
            </div>
            <div class="text-center">
                <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-2 text-xs font-medium text-gray-500">Secure Payment</p>
            </div>
            <div class="text-center">
                <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <p class="mt-2 text-xs font-medium text-gray-500">Easy Returns</p>
            </div>
        </div>

        <!-- Print Quality Features -->
        <div class="rounded-lg bg-gray-50 p-6">
            <h3 class="text-base font-medium text-gray-900">Premium Quality Features</h3>
            <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="relative flex items-start">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Museum-Quality Inks</h4>
                        <p class="mt-1 text-sm text-gray-500">Professional pigment inks for vibrant, long-lasting prints</p>
                    </div>
                </div>
                <div class="relative flex items-start">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Archival Paper</h4>
                        <p class="mt-1 text-sm text-gray-500">Premium fine art paper that preserves color and detail</p>
                    </div>
                </div>
                <div class="relative flex items-start">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Quality Inspected</h4>
                        <p class="mt-1 text-sm text-gray-500">Each print is individually checked for perfection</p>
                    </div>
                </div>
                <div class="relative flex items-start">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Secure Packaging</h4>
                        <p class="mt-1 text-sm text-gray-500">Protected shipping materials for safe delivery</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping & Returns -->
        <div class="rounded-lg bg-gray-50 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <div class="ml-4">
                        <h3 class="text-base font-medium text-gray-900">Fast & Free Shipping</h3>
                        <p class="mt-1 text-sm text-gray-500">5-7 business days with tracking</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <div class="ml-4">
                        <h3 class="text-base font-medium text-gray-900">30-Day Returns</h3>
                        <p class="mt-1 text-sm text-gray-500">Hassle-free return policy</p>
                    </div>
                </div>
            </div>
        </div>

        {{ $slot }}
    </div>
</div>

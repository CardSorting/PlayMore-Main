<x-app-layout>
    <div class="min-h-screen">
        <!-- Main Content -->
        {{ $slot }}

        <!-- Footer -->
        <footer class="border-t border-gray-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Security -->
                    <div class="flex items-start">
                        <div class="rounded-lg bg-gray-100 p-3">
                            <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Secure Checkout</h3>
                            <p class="mt-1 text-sm text-gray-500">Your payment information is protected</p>
                        </div>
                    </div>

                    <!-- Shipping -->
                    <div class="flex items-start">
                        <div class="rounded-lg bg-gray-100 p-3">
                            <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Free Shipping</h3>
                            <p class="mt-1 text-sm text-gray-500">On all orders within the US</p>
                        </div>
                    </div>

                    <!-- Support -->
                    <div class="flex items-start">
                        <div class="rounded-lg bg-gray-100 p-3">
                            <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Customer Support</h3>
                            <p class="mt-1 text-sm text-gray-500">Available 24/7 for assistance</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</x-app-layout>

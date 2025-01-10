@props(['nextStep'])

<div class="space-y-8">
    <!-- Starting Price -->
    <div class="rounded-lg border border-gray-200 bg-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Starting at</p>
                <p class="text-3xl font-bold tracking-tight text-gray-900">$19.99</p>
            </div>
            <div class="text-sm text-gray-500">
                <p>Multiple sizes available</p>
                <p>Free shipping included</p>
            </div>
        </div>

        <!-- Action Button -->
        <div class="mt-6">
            <a href="{{ $nextStep }}"
               class="flex w-full items-center justify-center rounded-md border border-transparent bg-blue-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Customize Your Print
            </a>
        </div>

        <!-- Trust Badges -->
        <div class="mt-6 grid grid-cols-2 gap-4 border-t border-gray-200 pt-6">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span class="ml-2 text-sm text-gray-500">Secure checkout</span>
            </div>
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span class="ml-2 text-sm text-gray-500">Free shipping</span>
            </div>
        </div>
    </div>
</div>

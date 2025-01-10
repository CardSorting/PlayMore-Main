@props(['title', 'description'])

<div class="space-y-6">
    <!-- Title and Description -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $title }}</h1>
        <p class="mt-3 text-base text-gray-500">{{ $description }}</p>
    </div>

    <!-- Print Quality Features -->
    <div class="border-t border-gray-200 pt-6">
        <h3 class="text-sm font-medium text-gray-900">Print Quality Features</h3>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="flex items-start">
                <svg class="h-5 w-5 flex-shrink-0 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-gray-900">Museum-Quality Inks</h4>
                    <p class="mt-1 text-sm text-gray-500">Professional pigment inks for vibrant, long-lasting prints</p>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="h-5 w-5 flex-shrink-0 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-gray-900">Archival Paper</h4>
                    <p class="mt-1 text-sm text-gray-500">Premium fine art paper that preserves color and detail</p>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="h-5 w-5 flex-shrink-0 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-gray-900">Quality Inspected</h4>
                    <p class="mt-1 text-sm text-gray-500">Each print is individually checked for perfection</p>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="h-5 w-5 flex-shrink-0 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-gray-900">Secure Packaging</h4>
                    <p class="mt-1 text-sm text-gray-500">Protected shipping materials for safe delivery</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Shipping Info -->
    <div class="border-t border-gray-200 pt-6">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-900">Free Standard Shipping</h3>
                <p class="mt-1 text-sm text-gray-500">5-7 business days</p>
            </div>
        </div>
    </div>

    {{ $slot }}
</div>

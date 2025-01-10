@props(['title', 'currentStep' => 1])

<div class="min-h-screen bg-gray-50">
    <!-- Progress Stepper -->
    <x-prints.progress-stepper :currentStep="$currentStep" />

    <!-- Main Content -->
    <main class="mx-auto max-w-7xl px-4 pt-8 pb-16 sm:px-6 sm:pt-12 sm:pb-24 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $title }}</h1>
            @if(isset($subtitle))
                <p class="mt-2 text-sm text-gray-500">{{ $subtitle }}</p>
            @endif
        </div>

        <!-- Two Column Layout -->
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12">
            <!-- Main Content Area -->
            <div class="lg:col-span-7">
                <!-- Form Content -->
                <div class="space-y-6">
                    {{ $form ?? '' }}
                </div>
            </div>

            <!-- Sidebar -->
            <div class="mt-10 lg:col-span-5 lg:mt-0">
                <div class="sticky top-6">
                    <!-- Order Summary -->
                    <div class="rounded-lg bg-white shadow-sm ring-1 ring-gray-900/5">
                        <dl class="flex flex-col">
                            <!-- Summary Header -->
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h2 class="text-base font-semibold leading-7 text-gray-900">Order Summary</h2>
                            </div>

                            <!-- Summary Content -->
                            <div class="flex-1 px-6 py-4">
                                {{ $summary ?? '' }}
                            </div>

                            <!-- Actions -->
                            @if(isset($actions))
                                <div class="border-t border-gray-200 px-6 py-4">
                                    {{ $actions }}
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Additional Content -->
                    @if(isset($sidebar))
                        <div class="mt-6">
                            {{ $sidebar }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-8 sm:grid-cols-3">
                <!-- Security Badge -->
                <div class="flex items-center">
                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900">Secure Checkout</h3>
                        <p class="mt-1 text-xs text-gray-500">256-bit SSL encryption</p>
                    </div>
                </div>

                <!-- Shipping Badge -->
                <div class="flex items-center">
                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900">Free Shipping</h3>
                        <p class="mt-1 text-xs text-gray-500">On all orders</p>
                    </div>
                </div>

                <!-- Support Badge -->
                <div class="flex items-center">
                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900">24/7 Support</h3>
                        <p class="mt-1 text-xs text-gray-500">Here to help</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

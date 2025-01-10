<x-prints.layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Progress Stepper -->
            <div class="border-b border-gray-200 mb-8">
                <x-prints.progress-stepper :currentStep="1" />
            </div>

            <div class="lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-8">
                <!-- Left Column - Product Gallery -->
                <div class="lg:col-span-7">
                    <div class="lg:sticky lg:top-20">
                        <x-prints.product-gallery
                            :image="$gallery->image_url"
                            :alt="$gallery->prompt"
                            :prompt="$gallery->prompt" />
                        
                        <!-- Product Info -->
                        <div class="mt-8">
                            <x-prints.product-details
                                title="Custom Art Print"
                                description="Transform your AI-generated masterpiece into a stunning, museum-quality print. Each piece is carefully produced using premium materials and expert craftsmanship." />
                        </div>

                        <!-- Additional Information Tabs -->
                        <div class="mt-8">
                            <x-prints.product-tabs />
                        </div>
                    </div>
                </div>

                <!-- Right Column - Overview Actions -->
                <div class="mt-10 lg:col-span-5 lg:mt-0">
                    <div class="lg:sticky lg:top-20">
                        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                            <div class="p-6">
                                <h2 class="text-2xl font-semibold text-gray-900">Custom Art Print</h2>
                                <p class="mt-2 text-sm text-gray-500">
                                    Create a stunning print of your AI-generated artwork. Available in various sizes and premium materials.
                                </p>

                                <!-- Features List -->
                                <div class="mt-6 space-y-4">
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 flex-shrink-0 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-gray-900">Multiple Size Options</h4>
                                            <p class="mt-1 text-sm text-gray-500">From mini prints to large gallery displays</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 flex-shrink-0 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-gray-900">Premium Materials</h4>
                                            <p class="mt-1 text-sm text-gray-500">Choose from various high-quality finishes</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 flex-shrink-0 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-gray-900">Free Shipping</h4>
                                            <p class="mt-1 text-sm text-gray-500">On all orders within the US</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="mt-8">
                                    <a href="{{ route('prints.select-size', $gallery) }}"
                                        class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Start Customizing
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-prints.layout>

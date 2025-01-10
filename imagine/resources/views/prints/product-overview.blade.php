<x-prints.layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Progress Stepper -->
            <div class="border-b border-gray-200 mb-8">
                <x-prints.progress-stepper :currentStep="1" />
            </div>

            <!-- Breadcrumbs -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li>
                        <a href="{{ route('dashboard') }}" class="hover:text-gray-700">Home</a>
                    </li>
                    <li>
                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </li>
                    <li>
                        <a href="#" class="font-medium text-gray-900">Custom Art Print</a>
                    </li>
                </ol>
            </nav>

            <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-12">
                <!-- Left Column - Product Gallery -->
                <div class="lg:sticky lg:top-20">
                    <x-prints.product-gallery
                        :image="$gallery->image_url"
                        :alt="$gallery->prompt"
                        :prompt="$gallery->prompt" />
                </div>

                <!-- Right Column - Product Info & Actions -->
                <div class="mt-10 lg:mt-0">
                    <div class="lg:sticky lg:top-20">
                        <!-- Product Title & Prompt -->
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Custom Art Print</h1>
                            <p class="mt-2 text-lg text-gray-600 italic">"{{ $gallery->prompt }}"</p>
                            <div class="mt-4 flex items-center">
                                <div class="flex items-center">
                                    <svg class="text-yellow-400 h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <p class="ml-2 text-sm text-gray-600">AI-Generated Masterpiece</p>
                                </div>
                                <div class="ml-4 border-l border-gray-300 pl-4">
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                        Ready to Print
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Price Range & Features -->
                        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                            <div class="p-6">
                                <div class="mb-6">
                                    <p class="text-3xl font-bold tracking-tight text-gray-900">
                                        From $19.99
                                        <span class="ml-2 text-lg font-normal text-gray-500">USD</span>
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">Price varies by size and material</p>
                                </div>

                                <!-- Features Grid -->
                                <div class="grid grid-cols-2 gap-6 border-t border-gray-200 py-6">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Materials</h4>
                                        <ul class="mt-2 space-y-2 text-sm text-gray-500">
                                            <li class="flex items-center">
                                                <svg class="mr-2 h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Premium Lustre
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="mr-2 h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Metallic
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="mr-2 h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Canvas
                                            </li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Sizes</h4>
                                        <ul class="mt-2 space-y-2 text-sm text-gray-500">
                                            <li class="flex items-center">
                                                <svg class="mr-2 h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                8" × 10"
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="mr-2 h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                12" × 16"
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="mr-2 h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                16" × 20"
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Shipping & Returns -->
                                <div class="border-t border-gray-200 py-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span class="ml-2 text-sm text-gray-600">Free shipping in US</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span class="ml-2 text-sm text-gray-600">30-day returns</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="mt-6 space-y-4">
                                    <a href="{{ route('prints.select-size', ['gallery' => $gallery]) }}"
                                        class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-4 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Choose Size & Material
                                        <svg class="ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <p class="text-center text-sm text-gray-500">
                                        Starting at $19.99 • Free shipping
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Product Details Tabs -->
                        <div class="mt-8">
                            <x-prints.product-tabs />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-prints.layout>

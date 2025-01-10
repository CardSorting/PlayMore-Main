<div x-data="{ activeTab: 'details' }" class="mt-8 border-t border-gray-200">
    <!-- Tab Navigation -->
    <div class="bg-white">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Product Information">
                <button @click="activeTab = 'details'"
                        :class="{ 'border-indigo-600 text-indigo-600': activeTab === 'details',
                                 'border-transparent text-gray-700 hover:text-gray-800 hover:border-gray-300': activeTab !== 'details' }"
                        class="whitespace-nowrap border-b-2 py-6 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Product Details
                    <span class="ml-2 hidden rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 sm:inline-block">
                        Premium Quality
                    </span>
                </button>
                <button @click="activeTab = 'shipping'"
                        :class="{ 'border-indigo-600 text-indigo-600': activeTab === 'shipping',
                                 'border-transparent text-gray-700 hover:text-gray-800 hover:border-gray-300': activeTab !== 'shipping' }"
                        class="whitespace-nowrap border-b-2 py-6 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Shipping & Returns
                    <span class="ml-2 hidden rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 sm:inline-block">
                        Free Shipping
                    </span>
                </button>
                <button @click="activeTab = 'reviews'"
                        :class="{ 'border-indigo-600 text-indigo-600': activeTab === 'reviews',
                                 'border-transparent text-gray-700 hover:text-gray-800 hover:border-gray-300': activeTab !== 'reviews' }"
                        class="whitespace-nowrap border-b-2 py-6 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Reviews
                    <span class="ml-2 hidden rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 sm:inline-block">
                        4.9 (150+)
                    </span>
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Panels -->
    <div class="py-10">
        <!-- Product Details -->
        <div x-show="activeTab === 'details'" class="space-y-10">
            <!-- Materials Comparison -->
            <section>
                <h3 class="text-lg font-medium text-gray-900">Premium Materials</h3>
                <div class="mt-6 grid grid-cols-1 gap-y-8 sm:grid-cols-3 sm:gap-x-6">
                    <div class="relative rounded-lg border border-gray-200 p-6">
                        <div class="absolute -top-4 left-4">
                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-0.5 text-sm font-medium text-indigo-800">
                                Most Popular
                            </span>
                        </div>
                        <div class="flex flex-col items-center text-center">
                            <span class="text-sm font-medium text-gray-900">Premium Lustre</span>
                            <p class="mt-2 text-sm text-gray-500">Professional-grade photo paper with a fine grain pebble texture</p>
                            <div class="mt-4 rounded-lg bg-gray-100 px-3 py-1">
                                <span class="text-xs font-medium text-gray-800">From $19.99</span>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-6">
                        <div class="flex flex-col items-center text-center">
                            <span class="text-sm font-medium text-gray-900">Metallic</span>
                            <p class="mt-2 text-sm text-gray-500">High-gloss finish with a stunning metallic appearance</p>
                            <div class="mt-4 rounded-lg bg-gray-100 px-3 py-1">
                                <span class="text-xs font-medium text-gray-800">From $24.99</span>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-6">
                        <div class="flex flex-col items-center text-center">
                            <span class="text-sm font-medium text-gray-900">Canvas</span>
                            <p class="mt-2 text-sm text-gray-500">Gallery-quality canvas with a textured, matte finish</p>
                            <div class="mt-4 rounded-lg bg-gray-100 px-3 py-1">
                                <span class="text-xs font-medium text-gray-800">From $29.99</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quality Features -->
            <section>
                <h3 class="text-lg font-medium text-gray-900">Quality Features</h3>
                <dl class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-6">
                        <dt class="flex items-center text-sm font-medium text-gray-900">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span class="ml-2">Archival Quality</span>
                        </dt>
                        <dd class="mt-3 text-sm text-gray-500">
                            Our prints are rated to last 100+ years without fading when displayed under glass.
                        </dd>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-6">
                        <dt class="flex items-center text-sm font-medium text-gray-900">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                            <span class="ml-2">Color Accuracy</span>
                        </dt>
                        <dd class="mt-3 text-sm text-gray-500">
                            Professional color calibration ensures your print matches what you see on screen.
                        </dd>
                    </div>
                </dl>
            </section>
        </div>

        <!-- Shipping & Returns -->
        <div x-show="activeTab === 'shipping'" class="space-y-10">
            <!-- Shipping Options -->
            <section>
                <h3 class="text-lg font-medium text-gray-900">Shipping Information</h3>
                <div class="mt-6 overflow-hidden rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Shipping Method
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Delivery Time
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Cost
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="ml-2 text-sm text-gray-900">Standard Shipping</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">5-7 business days</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                        FREE
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <span class="ml-2 text-sm text-gray-900">Express Shipping</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">2-3 business days</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">$14.99</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Returns Policy -->
            <section>
                <h3 class="text-lg font-medium text-gray-900">Returns & Refunds</h3>
                <div class="mt-6 rounded-lg border border-gray-200 bg-white p-6">
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">30-Day Money-Back Guarantee</h4>
                            <p class="mt-1 text-sm text-gray-500">
                                Not satisfied? Return within 30 days for a full refund, no questions asked.
                                We'll even cover the return shipping.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900">What's Covered</h5>
                                <ul class="mt-2 space-y-2 text-sm text-gray-500">
                                    <li class="flex items-center">
                                        <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="ml-2">Print quality issues</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="ml-2">Damaged during shipping</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="ml-2">Wrong size or material</span>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h5 class="text-sm font-medium text-gray-900">How to Return</h5>
                                <ol class="mt-2 space-y-2 text-sm text-gray-500">
                                    <li>1. Contact our support team</li>
                                    <li>2. Receive a prepaid return label</li>
                                    <li>3. Ship the item back</li>
                                    <li>4. Refund processed in 3-5 days</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Reviews -->
        <div x-show="activeTab === 'reviews'" class="space-y-10">
            <!-- Review Summary -->
            <section>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Customer Reviews</h3>
                        <p class="mt-1 text-sm text-gray-500">Based on 150+ reviews</p>
                    </div>
                    <div class="flex items-center">
                        <div class="flex items-center">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="ml-2 text-sm font-medium text-gray-900">4.9 out of 5</p>
                    </div>
                </div>

                <!-- Rating Breakdown -->
                <div class="mt-6">
                    <div class="space-y-2">
                        @foreach([5, 4, 3, 2, 1] as $rating)
                            <div class="flex items-center text-sm">
                                <span class="w-12 text-gray-900">{{ $rating }} star</span>
                                <div class="ml-4 flex-1">
                                    <div class="h-2 rounded-full bg-gray-200">
                                        <div class="h-2 rounded-full bg-yellow-400"
                                             style="width: {{ $rating === 5 ? '75%' : ($rating === 4 ? '17%' : '3%') }}">
                                        </div>
                                    </div>
                                </div>
                                <span class="ml-4 w-12 text-right text-gray-500">
                                    {{ $rating === 5 ? '75%' : ($rating === 4 ? '17%' : '3%') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Review List -->
            <section class="mt-10">
                <h3 class="sr-only">Recent reviews</h3>
                <div class="space-y-8">
                    <div class="rounded-lg border border-gray-200 bg-white p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex items-center

    <!-- Tab Panels -->
    <div class="py-6">
        <!-- Shipping & Returns -->
        <div x-show="activeTab === 'shipping'" class="space-y-6">
            <div>
                <h3 class="text-sm font-medium text-gray-900">Free Standard Shipping</h3>
                <div class="mt-4 space-y-4">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm text-gray-500">Free standard shipping on all orders</p>
                    </div>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm text-gray-500">Delivery in 5-7 business days</p>
                    </div>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm text-gray-500">Secure packaging for safe delivery</p>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-900">Easy Returns</h3>
                <p class="mt-4 text-sm text-gray-500">
                    Not satisfied with your print? Return it within 30 days for a full refund or replacement.
                    We'll even cover the return shipping costs.
                </p>
            </div>
        </div>

        <!-- Print Quality -->
        <div x-show="activeTab === 'quality'" class="space-y-6">
            <div>
                <h3 class="text-sm font-medium text-gray-900">Museum-Quality Materials</h3>
                <div class="mt-4 prose prose-sm text-gray-500">
                    <p>Our prints are produced using the finest materials and techniques:</p>
                    <ul class="mt-4 space-y-3">
                        <li>Professional pigment inks for vibrant, long-lasting colors</li>
                        <li>Archival fine art paper that preserves detail and prevents fading</li>
                        <li>Individual quality inspection for every print</li>
                        <li>Color-calibrated printing process for accuracy</li>
                    </ul>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-900">Quality Guarantee</h3>
                <p class="mt-4 text-sm text-gray-500">
                    Every print is backed by our quality guarantee. If you're not completely satisfied with the print quality,
                    we'll replace it or provide a full refund.
                </p>
            </div>
        </div>

        <!-- Reviews -->
        <div x-show="activeTab === 'reviews'" class="space-y-6">
            <div class="flex items-center">
                <div class="flex items-center">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                <p class="ml-3 text-sm text-gray-500">4.9 out of 5 stars (150+ reviews)</p>
            </div>
            <div class="space-y-8">
                <div class="border-b border-gray-200 pb-6">
                    <div class="flex items-center mb-2">
                        <div class="flex items-center">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="ml-2 text-sm text-gray-500">Sarah K. · 2 weeks ago</p>
                    </div>
                    <p class="text-sm text-gray-500">
                        "The print quality is absolutely stunning. The colors are vibrant and the detail is incredible.
                        I'm already planning to order more prints!"
                    </p>
                </div>
                <div class="border-b border-gray-200 pb-6">
                    <div class="flex items-center mb-2">
                        <div class="flex items-center">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="ml-2 text-sm text-gray-500">Michael R. · 1 month ago</p>
                    </div>
                    <p class="text-sm text-gray-500">
                        "Fast shipping and excellent packaging. The print arrived in perfect condition and looks amazing on my wall.
                        The quality exceeded my expectations."
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

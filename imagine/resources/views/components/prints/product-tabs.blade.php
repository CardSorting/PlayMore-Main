<div x-data="{ activeTab: 'shipping' }" class="mt-8 border-t border-gray-200">
    <!-- Tab Navigation -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Product Information">
            <button @click="activeTab = 'shipping'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'shipping',
                             'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'shipping' }"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                Shipping & Returns
            </button>
            <button @click="activeTab = 'quality'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'quality',
                             'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'quality' }"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                Print Quality
            </button>
            <button @click="activeTab = 'reviews'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'reviews',
                             'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'reviews' }"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                Reviews
            </button>
        </nav>
    </div>

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

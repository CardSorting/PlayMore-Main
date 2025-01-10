<x-public-layout>
    <!-- Back to Store -->
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('public.gallery.store', ['user' => $user->name]) }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-5 h-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to {{ $user->name }}'s Store
        </a>
    </div>

    <!-- Store Header -->
    <x-store.store-header 
        :user="$user"
        :sellerInfo="$sellerInfo"
        :stats="$stats"
    />

    <!-- Main Product Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mt-8 grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-2">
            <!-- Left Column: Product Gallery -->
            <div class="lg:row-span-2">
                <div class="relative">
                    @if(!$gallery->is_available)
                        <div class="absolute top-4 right-4 z-10">
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-gray-800 text-white">
                                Sold Out
                            </span>
                        </div>
                    @endif
                    
                    @if($gallery->getPopularityScore() > 0.7)
                        <div class="absolute top-4 left-4 z-10">
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Popular
                            </span>
                        </div>
                    @endif

                    <x-prints.product-gallery 
                        :image="$gallery->image_url"
                        :alt="$gallery->prompt"
                        :prompt="$gallery->prompt"
                    />
                </div>

                <!-- Social Proof -->
                <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            {{ $gallery->views_count }} views
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                            </svg>
                            {{ $gallery->printOrders()->count() }} sold
                        </div>
                    </div>
                    <span>Added {{ $gallery->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <!-- Right Column: Product Info & Purchase -->
            <div class="lg:max-w-lg">
                <x-prints.product-details 
                    :title="$gallery->prompt"
                    description="Transform your space with this unique AI-generated artwork. Each print is crafted with premium materials and expert attention to detail, ensuring museum-quality results that will last a lifetime."
                    :nextStep="$gallery->is_available ? route('prints.store', ['gallery' => $gallery]) : null"
                    :author="$user->name"
                    :price="$gallery->price"
                    :isAvailable="$gallery->is_available"
                />

                <!-- Shipping Information -->
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <h3 class="text-sm font-medium text-gray-900">Shipping Information</h3>
                    <div class="mt-4 space-y-4">
                        <div class="flex items-start">
                            <svg class="flex-shrink-0 h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700">Ships from {{ $sellerInfo->shipsFrom }}</p>
                                <p class="mt-1 text-sm text-gray-500">Available to ship to: {{ implode(', ', $sellerInfo->shipsTo) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Items -->
        @if(count($similarItems) > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Similar Items</h2>
                <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    @foreach($similarItems as $item)
                        <x-store.gallery-item :gallery="$item" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-public-layout>

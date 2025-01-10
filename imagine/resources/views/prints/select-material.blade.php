<x-prints.layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Progress Stepper -->
            <div class="border-b border-gray-200 mb-8">
                <x-prints.progress-stepper :currentStep="3" />
            </div>

            <div class="lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-8">
                <!-- Left Column - Product Gallery -->
                <div class="lg:col-span-7">
                    <div class="lg:sticky lg:top-20">
                        <x-prints.product-gallery
                            :image="$gallery->image_url"
                            :alt="$gallery->prompt"
                            :prompt="$gallery->prompt" />
                            
                        <!-- Selected Size Info -->
                        <div class="mt-4 rounded-md bg-gray-50 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Selected Size</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $sizes[$size]['name'] }} ({{ $sizes[$size]['dimensions'] }})</p>
                                </div>
                                <a href="{{ route('prints.overview', $gallery) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Change</a>
                            </div>
                        </div>
                        
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

                <!-- Right Column - Material Purchase Actions -->
                <div class="mt-10 lg:col-span-5 lg:mt-0">
                    <form method="POST" action="{{ route('prints.store', $gallery) }}" class="lg:sticky lg:top-20">
                        @csrf
                        <input type="hidden" name="gallery_id" value="{{ $gallery->id }}">
                        <input type="hidden" name="size" value="{{ $size }}">
                        
                        <x-prints.material-purchase-actions 
                            :sizes="$sizes"
                            :materials="config('prints.materials')"
                            :selectedSize="$size" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-prints.layout>

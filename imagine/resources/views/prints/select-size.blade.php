<x-prints.layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Progress Stepper -->
            <div class="border-b border-gray-200 mb-8">
                <x-prints.progress-stepper :currentStep="2" />
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
                                description="Transform your AI-generated masterpiece into a stunning, museum-quality print. Each piece is carefully produced using premium materials and expert craftsmanship.">
                                
                                <!-- Size Selection -->
                                <div class="mt-8">
                                    <x-prints.size-selector
                                        :sizes="$sizes" />
                                </div>
                            </x-prints.product-details>

                            <!-- Additional Information Tabs -->
                            <div class="mt-8">
                                <x-prints.product-tabs />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Size Purchase Actions -->
                <div class="mt-10 lg:col-span-5 lg:mt-0">
                    <form method="POST" action="{{ route('prints.store-size', $gallery) }}" class="lg:sticky lg:top-20">
                        @csrf
                        <input type="hidden" name="gallery_id" value="{{ $gallery->id }}">
                        
                        <x-prints.size-purchase-actions 
                            :sizes="$sizes" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-prints.layout>

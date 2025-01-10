<x-prints.layout>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-8">
                <!-- Left Column - Product Gallery -->
                <div class="lg:sticky lg:top-0">
                    <x-prints.product-gallery
                        :image="$gallery->image_url"
                        :alt="$gallery->prompt"
                        :prompt="$gallery->prompt" />
                </div>

                <!-- Right Column - Product Details -->
                <div class="mt-10 lg:mt-0">
                    <form method="POST" action="{{ route('prints.store', $gallery) }}">
                        @csrf
                        
                        <!-- Product Info -->
                        <x-prints.product-details
                            title="Custom Art Print"
                            description="Transform your AI-generated masterpiece into a stunning, museum-quality print. Each piece is carefully produced using premium materials and expert craftsmanship.">
                            
                            <!-- Size Selection -->
                            <div class="mt-8">
                                <x-prints.size-selector
                                    :sizes="$sizes"
                                    :selectedSize="old('size')" />
                            </div>
                        </x-prints.product-details>

                        <!-- Purchase Actions -->
                        <x-prints.purchase-actions :sizes="$sizes" />

                        <!-- Additional Information Tabs -->
                        <x-prints.product-tabs />
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-prints.layout>

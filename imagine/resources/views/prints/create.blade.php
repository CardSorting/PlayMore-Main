<x-prints.layout>
    <div x-data="{ 
        selectedSize: '{{ old('size', '') }}',
        selectedMaterial: '{{ old('material', 'premium_lustre') }}',
        sizes: @js($sizes),
        materials: @js(config('prints.materials')),
        get price() {
            if (!this.selectedSize) return 0;
            const basePrice = this.sizes[this.selectedSize].price;
            const multiplier = this.materials[this.selectedMaterial].price_multiplier;
            return basePrice * multiplier;
        },
        get formattedPrice() {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(this.price / 100);
        }
    }" class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
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

                <!-- Right Column - Purchase Actions -->
                <div class="mt-10 lg:col-span-5 lg:mt-0">
                    <form method="POST" action="{{ route('prints.store', $gallery) }}" class="lg:sticky lg:top-20">
                        @csrf
                        <input type="hidden" name="gallery_id" value="{{ $gallery->id }}">
                        <input type="hidden" name="size" x-model="selectedSize">
                        <input type="hidden" name="material" x-model="selectedMaterial">
                        
                        <x-prints.purchase-actions 
                            :sizes="$sizes"
                            :materials="config('prints.materials')" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-prints.layout>

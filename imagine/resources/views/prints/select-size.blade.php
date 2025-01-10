<x-prints.layout>
    <div x-data="{ 
        selectedSize: '{{ old('size', '') }}',
        sizes: @js($sizes),
        get price() {
            if (!this.selectedSize) return 0;
            return this.sizes[this.selectedSize].price;
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
            <!-- Progress Stepper -->
            <div class="mb-8">
                <x-prints.progress-stepper :current-step="1" />
            </div>

            <div class="lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-8">
                <!-- Left Column - Product Gallery -->
                <div class="lg:col-span-7">
                    <div class="lg:sticky lg:top-20">
                        <x-prints.product-gallery
                            :image="$gallery->image_url"
                            :alt="$gallery->prompt"
                            :prompt="$gallery->prompt" />
                    </div>
                </div>

                <!-- Right Column - Size Selection -->
                <div class="mt-10 lg:col-span-5 lg:mt-0">
                    <form method="POST" action="{{ route('prints.store-size', $gallery) }}" class="lg:sticky lg:top-20">
                        @csrf
                        <input type="hidden" name="gallery_id" value="{{ $gallery->id }}">
                        <input type="hidden" name="size" x-model="selectedSize">
                        
                        <div class="rounded-lg bg-white px-4 py-6 sm:p-6 lg:p-8">
                            <h2 class="text-2xl font-semibold text-gray-900">Select Print Size</h2>
                            <p class="mt-2 text-sm text-gray-500">Choose the perfect size for your space.</p>
                            
                            <div class="mt-8">
                                <x-prints.size-selector :sizes="$sizes" />
                            </div>

                            <div class="mt-8">
                                <button type="submit"
                                    x-bind:disabled="!selectedSize"
                                    x-bind:class="{'opacity-50 cursor-not-allowed': !selectedSize}"
                                    class="w-full rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Continue to Material Selection
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-prints.layout>

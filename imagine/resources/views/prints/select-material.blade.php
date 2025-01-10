<x-prints.layout>
    <div x-data="{ 
        selectedMaterial: '{{ old('material', 'premium_lustre') }}',
        selectedSize: '{{ $size }}',
        sizes: @js($sizes),
        materials: @js(config('prints.materials')),
        get price() {
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
            <!-- Progress Stepper -->
            <div class="mb-8">
                <x-prints.progress-stepper :current-step="2" />
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
                                <a href="{{ route('prints.select-size', $gallery) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Change</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Material Selection -->
                <div class="mt-10 lg:col-span-5 lg:mt-0">
                    <form method="POST" action="{{ route('prints.store', $gallery) }}" class="lg:sticky lg:top-20">
                        @csrf
                        <input type="hidden" name="gallery_id" value="{{ $gallery->id }}">
                        <input type="hidden" name="size" value="{{ $size }}">
                        <input type="hidden" name="material" x-model="selectedMaterial">
                        
                        <div class="rounded-lg bg-white px-4 py-6 sm:p-6 lg:p-8">
                            <h2 class="text-2xl font-semibold text-gray-900">Select Print Material</h2>
                            <p class="mt-2 text-sm text-gray-500">Choose the perfect finish for your artwork.</p>
                            
                            <div class="mt-8 space-y-4">
                                <template x-for="(material, id) in materials" :key="id">
                                    <div>
                                        <label :for="'material-' + id" class="relative block cursor-pointer rounded-lg border p-4 focus:outline-none"
                                            :class="selectedMaterial === id ? 'border-indigo-600 ring-2 ring-indigo-600' : 'border-gray-300'">
                                            <input type="radio" :id="'material-' + id" name="material-radio" :value="id"
                                                x-model="selectedMaterial" class="sr-only">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span x-text="material.name" class="block text-sm font-medium text-gray-900"></span>
                                                    <span x-text="material.description" class="mt-1 block text-sm text-gray-500"></span>
                                                </div>
                                                <div class="ml-4">
                                                    <span class="text-sm font-medium text-gray-900" x-text="'×' + material.price_multiplier"></span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </template>
                            </div>

                            <div class="mt-8">
                                <div class="flex items-center justify-between">
                                    <span class="text-base font-medium text-gray-900">Total Price</span>
                                    <span class="text-base font-medium text-gray-900" x-text="formattedPrice"></span>
                                </div>
                            </div>

                            <div class="mt-8">
                                <button type="submit"
                                    class="w-full rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Continue to Checkout
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-prints.layout>

<x-prints.layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Main Product Section -->
        <div class="mt-8 grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-2">
            <!-- Left Column: Product Gallery -->
            <div class="lg:row-span-2">
                <x-prints.product-gallery 
                    :image="$gallery->image_url"
                    :alt="$gallery->prompt"
                    :prompt="$gallery->prompt"
                />
            </div>

            <!-- Right Column: Product Info & Purchase -->
            <div class="lg:max-w-lg">
                <x-prints.product-details 
                    :title="$gallery->prompt"
                    description="Transform your space with this unique AI-generated artwork. Each print is crafted with premium materials and expert attention to detail, ensuring museum-quality results that will last a lifetime."
                    :next-step="route('prints.select-size', ['gallery' => $gallery])"
                />

                <div class="mt-8">
                    <x-prints.purchase-actions
                        :nextStep="route('prints.select-size', ['gallery' => $gallery])"
                    />
                </div>
            </div>

        </div>

        <!-- Product Information -->
        <div class="mt-16 bg-gray-50 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-prints.product-tabs :gallery="$gallery" />
            </div>
        </div>
        </div>
    </div>
</x-prints.layout>

<x-prints.layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Progress Stepper -->
            <x-prints.progress-stepper :current-step="1" />

            <div class="mt-8">
                <!-- Product Gallery -->
                <x-prints.product-gallery 
                    :image="$gallery->image_url"
                    :alt="$gallery->prompt"
                    :prompt="$gallery->prompt"
                />

                <!-- Product Details -->
                <div class="mt-8">
                    <x-prints.product-details 
                        :title="$gallery->prompt"
                        description="Transform your space with this unique AI-generated artwork. Each print is crafted with premium materials and expert attention to detail, ensuring museum-quality results that will last a lifetime."
                        :next-step="route('prints.select-size', ['gallery' => $gallery])"
                    />
                </div>

                <!-- Product Tabs -->
                <div class="mt-8">
                    <x-prints.product-tabs :gallery="$gallery" />
                </div>

                <!-- Purchase Actions -->
                <div id="customize" class="mt-8">
                    <x-prints.purchase-actions
                        :sizes="config('prints.sizes')"
                        :materials="config('prints.materials')"
                        :gallery="$gallery"
                        next-step="{{ route('prints.select-size', ['gallery' => $gallery]) }}"
                        button-text="Choose Print Size"
                    />
                </div>
            </div>
        </div>
    </div>
</x-prints.layout>

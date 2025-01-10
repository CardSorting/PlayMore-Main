<x-prints.layout
    title="Create Your Print"
    currentStep="1">
    <x-slot name="subtitle">
        Customize your print with size and shipping details
    </x-slot>

    <x-slot name="form">
        <form method="POST" action="{{ route('prints.store', $gallery) }}">
            @csrf

            <!-- Image Preview -->
            <div class="mb-8">
                <x-prints.image-preview
                    :image="$gallery->image_url"
                    :alt="$gallery->prompt"
                    :prompt="$gallery->prompt" />
            </div>

            <!-- Size Selection -->
            <div class="mb-8">
                <x-prints.size-selector
                    :sizes="$sizes"
                    :selectedSize="old('size')" />
            </div>

            <!-- Shipping Information -->
            <div class="mb-8">
                <x-prints.address-form :errors="$errors" />
            </div>

            <!-- Submit Button -->
            <div class="mt-10 border-t border-gray-200 pt-6">
                <button type="submit"
                        class="w-full rounded-md bg-blue-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Continue to Payment
                </button>
            </div>
        </form>
    </x-slot>

    <x-slot name="summary">
        <div class="space-y-4">
            <!-- Print Details -->
            <div>
                <dt class="text-sm font-medium text-gray-900">Print Details</dt>
                <dd class="mt-1 text-sm text-gray-500">
                    <div x-data="{ selectedSize: '{{ old('size', '') }}' }" class="space-y-2">
                        <p x-show="!selectedSize" class="italic">Select a size to see price</p>
                        <template x-if="selectedSize">
                            <div>
                                <p class="font-medium" x-text="sizes[selectedSize].name"></p>
                                <p x-text="sizes[selectedSize].dimensions"></p>
                                <p class="mt-1 text-lg font-bold text-gray-900" x-text="'$' + sizes[selectedSize].price.toFixed(2)"></p>
                            </div>
                        </template>
                    </div>
                </dd>
            </div>

            <!-- Print Quality -->
            <div>
                <dt class="text-sm font-medium text-gray-900">Print Quality</dt>
                <dd class="mt-1 text-sm text-gray-500">
                    <ul class="list-disc pl-4 space-y-1">
                        <li>Museum-quality pigment inks</li>
                        <li>Archival fine art paper</li>
                        <li>Individually inspected</li>
                        <li>Securely packaged</li>
                    </ul>
                </dd>
            </div>

            <!-- Shipping -->
            <div>
                <dt class="text-sm font-medium text-gray-900">Shipping</dt>
                <dd class="mt-1 text-sm text-gray-500">
                    <p>Free standard shipping</p>
                    <p class="mt-1">Estimated delivery: 5-7 business days</p>
                </dd>
            </div>
        </div>
    </x-slot>

    <x-slot name="sidebar">
        <!-- Quality Guarantee -->
        <div class="rounded-lg bg-blue-50 p-6">
            <div class="flex">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-600">Quality Guarantee</h3>
                    <p class="mt-2 text-sm text-blue-500">
                        Every print is carefully inspected and guaranteed to meet our high-quality standards.
                        Not satisfied? We'll replace it or refund your money.
                    </p>
                </div>
            </div>
        </div>

        <!-- Customer Reviews -->
        <div class="mt-6">
            <h3 class="text-sm font-medium text-gray-900">Customer Reviews</h3>
            <div class="mt-2 flex items-center">
                <div class="flex items-center">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                <p class="ml-2 text-sm text-gray-500">4.9 out of 5 stars</p>
            </div>
            <div class="mt-4">
                <blockquote class="mt-2 text-sm italic text-gray-500">
                    "The print quality is absolutely stunning. The colors are vibrant and the detail is incredible."
                </blockquote>
            </div>
        </div>
    </x-slot>
</x-prints.layout>

@props(['sizes'])

<div x-data="{ 
    selectedSize: '{{ old('size', '') }}',
    sizes: @js($sizes),
    get price() {
        if (!this.selectedSize) return 0;
        return this.sizes[this.selectedSize].price / 100;
    },
    get formattedPrice() {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(this.price);
    },
    get selectedSizeName() {
        if (!this.selectedSize) return '';
        return this.sizes[this.selectedSize].name;
    }
}" class="w-full">
    <div class="rounded-xl bg-white shadow-lg ring-1 ring-gray-900/5">
        <!-- Selection Status -->
        <div class="p-4 text-center border-b border-gray-100">
            <div x-show="selectedSize" class="space-y-1">
                <div class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-1">
                    <svg class="h-4 w-4 text-indigo-600 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.414-1.414L9 10.586 7.707 9.293a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-indigo-700" x-text="selectedSizeName"></span>
                </div>
                <p class="text-2xl font-bold tracking-tight text-gray-900" x-text="formattedPrice"></p>
            </div>
            <div x-show="!selectedSize">
                <p class="text-sm font-medium text-gray-500">Select a size</p>
            </div>
        </div>

        <!-- Action Button -->
        <div class="p-4">
            <button type="submit"
                    x-bind:disabled="!selectedSize"
                    x-bind:class="{
                        'bg-indigo-600 hover:bg-indigo-500': selectedSize,
                        'bg-gray-100 text-gray-400': !selectedSize
                    }"
                    class="w-full rounded-full py-2.5 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors duration-200">
                <span x-show="!selectedSize">Choose Size</span>
                <span x-show="selectedSize" class="flex items-center justify-center">
                    Continue
                    <svg class="ml-1 -mr-0.5 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div>

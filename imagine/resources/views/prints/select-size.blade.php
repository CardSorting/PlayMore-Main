<x-prints.layout>
    <!-- Progress Stepper - Full width for prominence -->
    <div class="border-b border-gray-200 bg-white">
        <div class="mx-auto max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
            <x-prints.progress-stepper :currentStep="2" />
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <div class="mx-auto max-w-[1600px] px-4 pt-12 pb-32 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Select Print Size</h1>
                <p class="mt-4 text-lg leading-8 text-gray-600">
                    Choose the perfect size to display your artwork
                </p>
            </div>

            <!-- Size Selection Form -->
            <form method="POST" action="{{ route('prints.store-size', $gallery) }}" class="relative">
                @csrf
                <input type="hidden" name="gallery_id" value="{{ $gallery->id }}">
                
                <!-- Main Content Area -->
                <div class="lg:grid lg:grid-cols-12 lg:gap-x-8">
                    <!-- Size Options - Full Width -->
                    <div class="lg:col-span-11">
                        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 p-6">
                            <x-prints.size-selector :sizes="$sizes" />
                        </div>
                    </div>

                    <!-- Purchase Actions - Sticky sidebar -->
                    <div class="hidden lg:block lg:col-span-1">
                        <div class="sticky top-8">
                            <x-prints.size-purchase-actions :sizes="$sizes" />
                        </div>
                    </div>
                </div>

                <!-- Mobile Purchase Actions - Fixed at bottom -->
                <div class="lg:hidden fixed bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-white via-white">
                    <x-prints.size-purchase-actions :sizes="$sizes" />
                </div>
            </form>
        </div>
    </div>
</x-prints.layout>

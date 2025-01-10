<x-prints.layout>
    <!-- Progress Stepper - Full width for prominence -->
    <div class="border-b border-gray-200 bg-white">
        <div class="mx-auto max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
            <x-prints.progress-stepper :currentStep="3" />
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <div class="mx-auto max-w-[1600px] px-4 pt-12 pb-32 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Select Print Material</h1>
                <p class="mt-4 text-lg leading-8 text-gray-600">
                    Choose the perfect material to bring your artwork to life
                </p>
            </div>

            <!-- Material Selection Form -->
            <form x-data="{ 
                selectedMaterial: '{{ old('material', 'matte') }}',
                materials: @js($materials),
                getPrice(basePrice, material) {
                    return (basePrice / 100) * this.materials[material].price_multiplier;
                }
            }" method="POST" action="{{ route('prints.store-material', $gallery) }}" class="relative">
                @csrf

                <!-- Selected Size Info -->
                <div class="mb-8 bg-white rounded-xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <!-- Size Icon -->
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-lg bg-indigo-50 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Size Details -->
                                <div>
                                    @php
                                        $sizeInfo = null;
                                        $sizeCategory = null;
                                        foreach (config('prints.sizes') as $category) {
                                            if (isset($category['sizes'][$selectedSize])) {
                                                $sizeInfo = $category['sizes'][$selectedSize];
                                                $sizeCategory = $category['category'];
                                                break;
                                            }
                                        }
                                    @endphp
                                    <div class="flex flex-col">
                                        <div class="flex items-center">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $selectedSize }}</h3>
                                            @if($sizeCategory)
                                                <span class="ml-2 inline-flex items-center rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700">
                                                    {{ $sizeCategory }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($sizeInfo)
                                        <div class="mt-1 flex items-center text-sm text-gray-500">
                                            <svg class="h-4 w-4 text-gray-400 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            {{ $sizeInfo['width'] }}" × {{ $sizeInfo['height'] }}"
                                            <span class="mx-2">•</span>
                                            <svg class="h-4 w-4 text-gray-400 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            Size of {{ $sizeInfo['comparison_object'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Change Button -->
                            <a href="{{ route('prints.select-size', $gallery) }}" 
                               class="group flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Change
                                <svg class="ml-1 h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Price Info -->
                    @if($sizeInfo)
                        <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-3">
                            <div class="flex items-center text-sm">
                                <svg class="h-4 w-4 text-gray-400 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-500">
                                    Base price ${{ number_format($sizeInfo['price'] / 100, 2) }}
                                    <template x-if="selectedMaterial !== 'matte'">
                                        <span class="ml-1">
                                            • With <span x-text="materials[selectedMaterial].name.toLowerCase()"></span>: 
                                            <span class="font-medium text-gray-900" x-text="'$' + getPrice({{ $sizeInfo['price'] }}, selectedMaterial).toFixed(2)"></span>
                                        </span>
                                    </template>
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Error Message -->
                @error('material')
                    <div class="mb-8 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">{{ $message }}</h3>
                            </div>
                        </div>
                    </div>
                @enderror

                <!-- Material Selection Grid -->
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($materials as $key => $material)
                            <label class="relative flex cursor-pointer rounded-xl border-2 bg-white p-6 focus:outline-none transition-all duration-200 group hover:scale-[1.02]"
                                   :class="{
                                       'border-indigo-500 ring-2 ring-indigo-500 ring-offset-2 shadow-md': selectedMaterial === '{{ $key }}',
                                       'border-gray-200 hover:border-indigo-200 hover:shadow-sm': selectedMaterial !== '{{ $key }}'
                                   }">
                                <input type="radio"
                                       name="material"
                                       value="{{ $key }}"
                                       x-model="selectedMaterial"
                                       class="sr-only">
                                <div class="flex flex-col flex-1 relative">
                                    <!-- Selection Indicator -->
                                    <div class="absolute -top-2 -right-2 h-6 w-6 bg-indigo-500 rounded-full flex items-center justify-center shadow-sm transform scale-0 transition-transform duration-200"
                                         :class="{ 'scale-100': selectedMaterial === '{{ $key }}' }">
                                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-gray-900">{{ $material['name'] }}</h3>
                                        @if($material['price_multiplier'] > 1)
                                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700">
                                                +{{ number_format(($material['price_multiplier'] - 1) * 100) }}% premium
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">{{ $material['description'] }}</p>
                                    <ul class="mt-4 space-y-2">
                                        @foreach($material['features'] as $feature)
                                            <li class="flex items-center text-sm text-gray-600">
                                                <svg class="h-4 w-4 text-indigo-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Continue Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            Continue to Checkout
                            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-prints.layout>

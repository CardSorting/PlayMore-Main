<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Generate Image') }}
            </h2>
            <a href="{{ route('images.gallery') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-200">
                View Gallery
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('images.generate') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="prompt" class="block text-sm font-medium text-gray-700 mb-2">Image Description</label>
                        <textarea 
                            name="prompt" 
                            id="prompt"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Describe the image you want to generate..."
                            required
                        >{{ old('prompt') }}</textarea>
                        @error('prompt')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="aspect_ratio" class="block text-sm font-medium text-gray-700 mb-2">Aspect Ratio</label>
                            <select 
                                name="aspect_ratio" 
                                id="aspect_ratio"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="1:1">Square (1:1)</option>
                                <option value="16:9">Landscape (16:9)</option>
                                <option value="4:3">Standard (4:3)</option>
                            </select>
                        </div>

                        <div>
                            <label for="process_mode" class="block text-sm font-medium text-gray-700 mb-2">Process Mode</label>
                            <select 
                                name="process_mode" 
                                id="process_mode"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="relax">Relax - Better Quality</option>
                                <option value="fast">Fast - Standard Quality</option>
                                <option value="turbo">Turbo - Quick Results</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-gray-50 -mx-6 -mb-6 px-6 py-4 mt-6">
                        <button 
                            type="submit"
                            class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-200 flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Generate Image
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

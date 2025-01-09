<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Image Generation Status') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('images.gallery') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-200">
                    View Gallery
                </a>
                <a 
                    href="{{ route('images.create') }}" 
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200"
                    data-new-image-button
                >
                    Generate New Image
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div data-status-content>
                    <div class="mb-6">
                        <span class="font-semibold">Status:</span>
                        <span 
                            class="ml-2 px-3 py-1 rounded-full text-sm
                            @if($data['status'] === 'completed') bg-green-100 text-green-800
                            @elseif($data['status'] === 'failed') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif"
                            data-status="{{ $data['status'] }}"
                        >
                            {{ ucfirst($data['status']) }}
                        </span>
                    </div>

                    @if($data['status'] === 'completed' && isset($data['output']['image_urls']))
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($data['output']['image_urls'] as $index => $imageUrl)
                                <div class="relative group bg-white rounded-lg shadow-md overflow-hidden">
                                    <div 
                                        class="aspect-square relative cursor-pointer"
                                        onclick="window.statusService.handleImageClick(event, '{{ $imageUrl }}', {{ json_encode($data) }})"
                                    >
                                        <!-- Background Overlay -->
                                        <div class="absolute inset-0 bg-cover bg-center z-10"
                                             style="background-image: url('{{ $imageUrl }}');">
                                        </div>
                                        
                                        <!-- Actual image -->
                                        <img 
                                            src="{{ $imageUrl }}" 
                                            alt="Generated image {{ $index + 1 }}" 
                                            class="opacity-0 w-full h-full object-cover"
                                        >
                                        
                                        <!-- Download Button Overlay -->
                                        <div class="absolute inset-0 z-20 flex items-center justify-center bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <a 
                                                href="{{ $imageUrl }}" 
                                                download 
                                                target="_blank"
                                                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors duration-200"
                                                onclick="event.stopPropagation()"
                                            >
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif($data['status'] === 'failed')
                        <div class="text-red-600">
                            Error: {{ $data['error']['message'] ?? 'An unknown error occurred' }}
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center p-12">
                            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-500 mb-4"></div>
                            <p class="text-gray-600">
                                Please wait while your image is being generated...
                            </p>
                            <p class="text-sm text-gray-500 mt-2">
                                This may take a few minutes depending on the selected process mode.
                            </p>
                            <!-- Progress bar for auto-refresh -->
                            <div class="w-64 h-1 bg-gray-200 rounded-full mt-6 overflow-hidden">
                                <div 
                                    id="refreshProgress"
                                    class="h-full bg-blue-500 w-0"
                                ></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Image Details</h2>
                <button onclick="window.statusService.closeModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <img id="modalImage" src="" alt="Selected image" class="w-full h-auto rounded-lg shadow-md">
                </div>
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold mb-2">Prompt</h3>
                        <p id="modalPrompt" class="text-gray-600"></p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">Settings</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Aspect Ratio:</span> <span id="modalAspectRatio" class="text-gray-600"></span></p>
                            <p><span class="font-medium">Process Mode:</span> <span id="modalProcessMode" class="text-gray-600"></span></p>
                            <p><span class="font-medium">Task ID:</span> <span id="modalTaskId" class="text-gray-600"></span></p>
                            <p><span class="font-medium">Created:</span> <span id="modalCreated" class="text-gray-600"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/services/status-service.js') }}"></script>
</x-app-layout>

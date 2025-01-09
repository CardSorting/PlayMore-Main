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
                    <div class="space-y-6">
                        <!-- Feedback Message -->
                        <div class="text-center mb-8">
                            <p class="text-xl text-gray-700 font-medium animate-fade-in">
                                {{ $taskInfo['stage_info']['feedback'] }}
                            </p>
                        </div>
                        
                        <!-- Image Grid -->
                        <div class="grid grid-cols-2 gap-6">
                            @foreach($data['output']['image_urls'] as $index => $imageUrl)
                                <div class="relative group bg-white rounded-lg shadow-lg overflow-hidden animate-slide-up" style="animation-delay: {{ $index * 150 }}ms">
                                    <div 
                                        class="aspect-square relative cursor-pointer transform transition-transform duration-300 hover:scale-[1.02]"
                                        onclick="handleImageClick(event, '{{ $imageUrl }}', {{ json_encode($data) }})"
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
                                        
                                        <!-- Info Overlay -->
                                        <div class="absolute inset-0 z-20 flex items-center justify-center bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                            <p class="text-white text-sm px-4 py-2 text-center">{{ $data['input']['prompt'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Feedback History -->
                        @if(isset($taskInfo['feedback_history']) && count($taskInfo['feedback_history']) > 0)
                            <div class="mt-12 pt-8 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Creation Journey</h3>
                                <div class="space-y-3">
                                    @foreach($taskInfo['feedback_history'] as $index => $feedback)
                                        <div class="animate-fade-in" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                                            <p class="text-gray-600 italic">{{ $feedback }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif($data['status'] === 'failed')
                    <div class="text-center space-y-6">
                        <div class="text-red-600 mb-4">
                            Error: {{ $data['error']['message'] ?? 'An unknown error occurred' }}
                        </div>
                        <p class="text-xl text-gray-700">{{ $taskInfo['stage_info']['feedback'] }}</p>
                        <a 
                            href="{{ route('images.create') }}"
                            class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transform transition-all duration-300 hover:scale-105 hover:shadow-lg"
                        >
                            Try Again
                        </a>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center p-12">
                        <!-- Progress Circle -->
                        <div class="relative">
                            <svg class="w-32 h-32" viewBox="0 0 100 100">
                                <!-- Background circle -->
                                <circle 
                                    class="text-gray-200" 
                                    stroke-width="8" 
                                    stroke="currentColor" 
                                    fill="transparent" 
                                    r="42" 
                                    cx="50" 
                                    cy="50"
                                />
                                <!-- Progress circle -->
                                <circle 
                                    class="text-blue-500 transition-all duration-1000 ease-in-out" 
                                    stroke-width="8" 
                                    stroke="currentColor" 
                                    fill="transparent" 
                                    r="42" 
                                    cx="50" 
                                    cy="50"
                                    stroke-dasharray="264"
                                    stroke-dashoffset="{{ 264 - ($taskInfo['stage_info']['progress'] / 100 * 264) }}"
                                    transform="rotate(-90 50 50)"
                                />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-semibold">{{ $taskInfo['stage_info']['progress'] }}%</span>
                            </div>
                        </div>

                        <!-- Status Message -->
                        <div class="mt-8 text-center space-y-4">
                            <p class="text-xl text-gray-700 font-medium animate-pulse">
                                {{ $taskInfo['stage_info']['message'] }}
                            </p>
                            <p class="text-gray-600 italic transition-opacity duration-300">
                                {{ $taskInfo['stage_info']['feedback'] }}
                            </p>
                        </div>

                        <!-- Substages Progress -->
                        <div class="w-full max-w-md mt-8">
                            @foreach($taskInfo['stage_info']['substages'] as $index => $substage)
                                <div class="flex items-center mb-4 transition-all duration-300 {{ $index <= $taskInfo['current_substage'] ? 'opacity-100' : 'opacity-50' }}">
                                    <div class="w-8 h-8 flex-shrink-0 mr-4">
                                        @if($index < $taskInfo['current_substage'])
                                            <!-- Completed -->
                                            <svg class="w-8 h-8 text-green-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @elseif($index === $taskInfo['current_substage'])
                                            <!-- Current -->
                                            <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                                        @else
                                            <!-- Pending -->
                                            <div class="w-8 h-8 border-4 border-gray-200 rounded-full transition-colors duration-300"></div>
                                        @endif
                                    </div>
                                    <span class="text-base {{ $index <= $taskInfo['current_substage'] ? 'text-gray-900 font-medium' : 'text-gray-500' }}">
                                        {{ $substage }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Auto-refresh Progress -->
                        <div class="w-64 h-1 bg-gray-200 rounded-full mt-12 overflow-hidden">
                            <div 
                                id="refreshProgress"
                                class="h-full bg-blue-500 w-0 transition-all duration-[5000ms] ease-linear"
                            ></div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            Next update in <span id="refreshCountdown" class="font-medium">5</span> seconds
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Image Details</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <img id="modalImage" src="" alt="Selected image" class="w-full h-auto rounded-lg shadow-md">
            </div>
            <div class="space-y-6">
                <div>
                    <h3 class="font-semibold mb-2">Prompt</h3>
                    <p id="modalPrompt" class="text-gray-600"></p>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">Settings</h3>
                    <div class="space-y-3">
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

<style>
    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fade-in {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .animate-slide-up {
        animation: slide-up 0.5s ease-out forwards;
    }

    .animate-fade-in {
        animation: fade-in 0.5s ease-out forwards;
    }

    [data-status-content] {
        transition: opacity 300ms ease-in-out;
    }

    #imageModal .bg-white {
        transition: all 300ms ease-in-out;
    }

    #imageModal.flex .bg-white {
        transform: scale(1);
        opacity: 1;
    }
</style>
<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            <!-- Creator Profile -->
            <div class="col-span-2">
                <div class="flex items-center space-x-6">
                    <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-full bg-gray-200 flex items-center justify-center">
                        <svg class="h-12 w-12 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center space-x-3">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}'s Store</h1>
                            @if($sellerInfo->isOnline)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Online
                                </span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Member since {{ $stats->joinedDate }}</p>
                        @if($user->bio)
                            <p class="mt-2 text-sm text-gray-600">{{ $user->bio }}</p>
                        @endif
                        <div class="mt-3 flex items-center space-x-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Artworks</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $stats->artworks }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Sales</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $stats->sales }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Rating</p>
                                <div class="mt-1 flex items-center">
                                    <p class="text-lg font-semibold text-gray-900">{{ number_format($stats->rating, 1) }}</p>
                                    <div class="ml-1 flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="h-5 w-5 {{ $i <= floor($stats->rating) ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                 viewBox="0 0 20 20" 
                                                 fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm text-gray-500">({{ $stats->totalRatings }})</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-8 lg:mt-0">
                <dl class="grid grid-cols-1 gap-y-4">
                    <div class="border-l border-gray-200 pl-4">
                        <dt class="text-sm font-medium text-gray-500">Response Time</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $sellerInfo->responseTime }}</dd>
                    </div>
                    <div class="border-l border-gray-200 pl-4">
                        <dt class="text-sm font-medium text-gray-500">Ships From</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $sellerInfo->shipsFrom }}</dd>
                    </div>
                    <div class="border-l border-gray-200 pl-4">
                        <dt class="text-sm font-medium text-gray-500">Ships To</dt>
                        <dd class="mt-1 text-sm text-gray-700">
                            {{ implode(', ', array_slice($sellerInfo->shipsTo, 0, 3)) }}
                            @if(count($sellerInfo->shipsTo) > 3)
                                <span class="text-gray-500">+{{ count($sellerInfo->shipsTo) - 3 }} more</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

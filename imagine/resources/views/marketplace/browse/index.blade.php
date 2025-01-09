<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Pack Grid -->
                    <div id="pack-grid-container">
                        @include('marketplace.browse.components.pack-grid', ['availablePacks' => $availablePacks])
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

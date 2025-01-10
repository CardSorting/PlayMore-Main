<x-app-layout>
    <!-- Amazon-style blue header -->
    <div class="bg-[#232f3e] border-b border-gray-300">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-medium text-white">Your Orders</h1>
                <a href="{{ route('profile.settings') }}" class="inline-flex items-center px-4 py-2 bg-[#febd69] border border-transparent rounded text-sm font-medium text-black hover:bg-[#f3a847] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#febd69]">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Account Settings
                </a>
            </div>
        </div>
    </div>

    <!-- Order Categories -->
    <div class="border-b border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="flex overflow-x-auto">
                <a href="#" class="flex-shrink-0 border-b-2 border-[#232f3e] px-6 py-3 text-sm font-medium text-[#232f3e]">
                    Print Orders
                </a>
                <a href="#" class="flex-shrink-0 border-b-2 border-transparent px-6 py-3 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                    Digital Orders
                </a>
                <a href="#" class="flex-shrink-0 border-b-2 border-transparent px-6 py-3 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                    Cancelled Orders
                </a>
            </div>
        </div>
    </div>

    <div class="py-6 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('profile.partials.print-orders-history')
        </div>
    </div>
</x-app-layout>

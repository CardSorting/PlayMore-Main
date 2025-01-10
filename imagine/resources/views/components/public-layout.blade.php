<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Imagine') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Main Navigation -->
        <nav class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Primary Navigation -->
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex flex-shrink-0 items-center">
                            <a href="{{ url('/') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        </div>
                        <!-- Primary Navigation Links -->
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ url('/explore') }}" 
                               class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                Explore
                            </a>
                            <a href="{{ url('/categories') }}" 
                               class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                Categories
                            </a>
                            <a href="{{ url('/trending') }}" 
                               class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                Trending
                            </a>
                        </div>
                    </div>
                    <!-- Right Navigation -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="flex-1 min-w-0 md:px-8 lg:px-0 xl:col-span-6">
                            <div class="flex items-center px-6 py-4 md:mx-auto md:max-w-3xl lg:mx-0 lg:max-w-none xl:px-0">
                                <div class="w-full">
                                    <label for="search" class="sr-only">Search</label>
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input id="search" name="search" class="block w-full rounded-md border-0 bg-gray-100 py-1.5 pl-10 pr-3 text-gray-900 placeholder:text-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6" placeholder="Search creators and artwork" type="search">
                                    </div>
                                </div>
                            </div>
                        </div>

                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Dashboard</a>
                            <a href="{{ url('/cart') }}" class="text-gray-400 hover:text-gray-500 relative">
                                <span class="sr-only">Shopping cart</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-blue-600 text-xs font-medium text-white flex items-center justify-center">0</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Register</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white mt-auto">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="border-t border-gray-200 py-8">
                    <p class="text-center text-sm text-gray-500">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Imagine') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

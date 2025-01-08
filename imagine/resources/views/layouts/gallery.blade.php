<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Third-party Libraries -->
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Card Effects -->
    <script src="{{ asset('js/mtg-card-3d-effect.js') }}"></script>

    <!-- Gallery Services (order matters) -->
    <script src="{{ asset('js/services/masonry-service.js') }}"></script>
    <script src="{{ asset('js/services/filter-service.js') }}"></script>
    <script src="{{ asset('js/services/sort-service.js') }}"></script>
    <script src="{{ asset('js/services/view-service.js') }}"></script>
    <script src="{{ asset('js/services/gallery-service.js') }}"></script>

    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        .text-shadow-lg {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .card-item {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        .filter-btn.active,
        .type-filter-btn.active {
            @apply ring-2 ring-blue-500;
        }

        .view-btn.active {
            @apply bg-gray-100;
        }

        /* Masonry transitions */
        .cards-masonry {
            transition: opacity 0.3s ease-in-out;
        }

        .cards-masonry .card-item {
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        /* Modal transitions */
        .modal-enter {
            opacity: 0;
            transform: scale(0.95);
        }

        .modal-enter-active {
            opacity: 1;
            transform: scale(1);
            transition: opacity 0.3s ease-out, transform 0.2s ease-out;
        }

        .modal-exit {
            opacity: 1;
            transform: scale(1);
        }

        .modal-exit-active {
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 0.2s ease-in, transform 0.2s ease-in;
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @hasSection('header')
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    @stack('scripts')

    <script>
        // Initialize gallery when all scripts are loaded
        window.addEventListener('gallery:ready', () => {
            // Additional initialization if needed
            console.log('Gallery initialized');
        });
    </script>
</body>
</html>

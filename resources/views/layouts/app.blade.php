<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name', 'Itinerary Planner') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/app.js') }}" defer></script>
    @endif
</head>

<body class="font-sans antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:outline-none focus:ring">Skip to main content</a>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main id="main-content">
            {{ $slot }}
        </main>
        @if (session('success'))
            <x-toast class="bg-green-600">
                {{ session('success') }}
            </x-toast>
        @endif
        @if (session('error'))
            <x-toast class="bg-red-600">
                {{ session('error') }}
            </x-toast>
        @endif
    </div>
    @stack('scripts')

</body>
<script src="//unpkg.com/alpinejs" defer></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />


</html>
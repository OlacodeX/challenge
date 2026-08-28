<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kinkoza') }}</title>

        <x-favicon />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="relative min-h-screen flex flex-col sm:justify-center items-center px-4 py-10 sm:py-0 bg-gray-50">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-white to-indigo-50/40 pointer-events-none"></div>

            <div class="relative w-full sm:max-w-md">
                <div class="text-center mb-8">
                    <a href="{{ route('home') }}" wire:navigate class="inline-block">
                        <span class="text-2xl font-bold text-gray-900 tracking-tight">{{ config('app.name', 'Kinkoza') }}</span>
                    </a>
                    <p class="mt-2 text-xs font-medium text-indigo-600 uppercase tracking-wide">B2B Marketplace</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 px-6 py-8 sm:px-8">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-center">
                    <a href="{{ route('home') }}" wire:navigate class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                        &larr; Back to listings
                    </a>
                </p>
            </div>
        </div>
    </body>
</html>

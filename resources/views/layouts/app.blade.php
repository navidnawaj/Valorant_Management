<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- Performance and SEO meta tags -->
        <meta name="description" content="Valorant team and scrim management application">
        <meta name="keywords" content="valorant, esports, team management, scrims">
        <meta name="author" content="Valorant Management">
        <meta name="robots" content="index, follow">
        
        <!-- PWA meta tags -->
        <meta name="theme-color" content="#4f46e5">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="ValMgmt">
        <meta name="mobile-web-app-capable" content="yes">
        
        <!-- Manifest -->
        <link rel="manifest" href="/manifest.json">
        
        <!-- Favicon and icons -->
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/assets/1360655.png">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts - Optimized loading -->
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link rel="dns-prefetch" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"></noscript>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Flowbite - Load asynchronously -->
        <script>
            // Load Flowbite only if needed
            document.addEventListener('DOMContentLoaded', function() {
                if (document.querySelector('[data-modal-toggle]') || 
                    document.querySelector('[data-dropdown-toggle]') ||
                    document.querySelector('[data-collapse-toggle]')) {
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js';
                    script.async = true;
                    document.head.appendChild(script);
                }
            });
        </script>
    </head>
    <body class="font-sans antialiased">
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
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>

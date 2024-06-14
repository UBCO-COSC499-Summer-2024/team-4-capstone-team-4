<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Authentication | {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/css/scrollbar.css', 'resources/css/form.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body>
        <x-auth-header />
        <main class="antialiased">
            <div class="auth-container">
               {{ $slot }}
            </div>
        </main>
        @livewireScripts
    </body>
</html>

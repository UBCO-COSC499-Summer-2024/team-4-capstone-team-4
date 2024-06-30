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
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/scrollbar.css', 'resources/css/form.css', 'resources/js/app.js', 'resources/js/staff.js', 'resources/css/import.css'])
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- Chart.js Library -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Styles -->
        @livewireStyles
        <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    </head>
    <body class="font-sans antialiased">
        <x-header />
        <main>
            @php
                $user = Auth::user();
            @endphp

            @if($user && $user->hasRoles(['admin', 'dept_head', 'dept_staff']))
                <x-sidebar :items="[
                    ['icon' => 'groups', 'href' => '/staff', 'title' => 'Staff'],
                    ['icon' => 'leaderboard', 'href' => '/leaderboard', 'title' => 'Leaderboard'],
                    ['icon' => 'upload_file', 'href' => '/import', 'title' => 'Import']
                ]" />
            @else
                <x-sidebar :items="[]" />
            @endif
            <section class="container">
                {{ $slot }}
            </section>
        </main>
        @stack('modals')

        @livewireScripts
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Insight') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.css">

        <!-- Scripts -->
        @vite(['resources/css/var.css', 'resources/css/app.css', 'resources/css/scrollbar.css', 'resources/css/form.css', 'resources/css/tabs.css', 'resources/css/toolbar.css', 'resources/css/switch.css', 'resources/css/toastify.css','resources/css/course-details.css',
        'resources/css/calendar.css', 'resources/css/card.css', 'resources/css/dropdown.css', 'resources/css/import.css', 'resources/css/svcr.css', 'resources/js/app.js', 'resources/js/events.js', 'resources/js/sidebar.js', 'resources/js/tabs.js',
         'resources/js/dropdown.js', 'resources/js/staff.js', 'resources/js/sortTable.js', 'resources/js/buttons.js','resources/js/coursedetails-search.js', 'resources/js/exportReport.js','resources/js/coursedetails-modal.js', 'resources/js/darkmode.js', 'resources/css/reports.css'])

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <!-- JSPDF Library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

        <!-- Chart.js Library -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Styles -->
        @livewireStyles
        {{-- @toastifyCss --}}
        <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    </head>
    <body class="font-sans antialiased {{ config('app.theme') === 'dark' ? 'dark' : '' }}">
        <x-header />
        <main>
            @php
                $user = Auth::user();
            @endphp

            @if($user && $user->hasRoles(['admin', 'dept_head', 'dept_staff']))
                <x-sidebar :items="[
                    ['icon' => 'work_history', 'href' => '/svcroles', 'title' => 'Service Roles'],
                    ['icon' => 'groups', 'href' => '/staff', 'title' => 'Staff'],
                    ['icon' => 'leaderboard', 'href' => '/leaderboard', 'title' => 'Leaderboard'],
                    ['icon' => 'upload_file', 'href' => '/import', 'title' => 'Course Import'],
                ]" />
            @else
                <x-sidebar :items="[]" />
            @endif
            <section class="ins-container">
                {{ $slot }}
            </section>
        </main>
        @stack('modals')
        @livewireScripts
        {{-- @toastifyJs --}}
        <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        {{-- <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script> --}}
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@floating-ui/core@1.6.4"></script>
        <script src="https://cdn.jsdelivr.net/npm/@floating-ui/dom@1.6.7"></script>
        @php
            $isProduction = config('app.env') === 'production';
        @endphp
        {{-- @if ($isProduction)
            <script src="https://unpkg.com/@popperjs/core@2"></script>
            <script src="https://unpkg.com/tippy.js@6"></script>
        @else
            <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
            <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>
        @endif --}}
    </body>
</html>

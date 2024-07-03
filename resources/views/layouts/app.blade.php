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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/scrollbar.css', 'resources/css/form.css', 'resources/css/tabs.css', 'resources/css/toolbar.css', 'resources/css/switch.css', 'resources/css/calendar.css', 'resources/css/card.css', 'resources/css/dropdown.css', 'resources/css/svcr.css', 'resources/js/app.js', 'resources/js/tabs.js', 'resources/js/dropdown.js'])

        <!-- Styles -->
        @livewireStyles
        <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    </head>
    <body class="font-sans antialiased">
        <x-header />
        <main>
            <x-sidebar :items="[]" />
            <section class="container">
                {{ $slot }}
            </section>
        </main>
        @stack('modals')
        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <script>
            Livewire.on('show-toast', (data) => {
                // it seems data is an array of objects
                data.forEach((toast) => {
                    Toastify({
                        text: toast.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        className: `toastify-${toast.type}`, // Optional styling
                        stopOnFocus: true,
                    }).showToast();
                });
            });

            Livewire.on('confirmDelete', (data) => {
                console.log(data);
                data.forEach((item) => {
                    if (confirm(item.message)) {
                        Livewire.dispatch('deleteServiceRole', item.serviceRoleId);
                    }
                })
            });

            Livewire.on('batchDeleteServiceRoles', (data) => {
                data = data[0];
                if (confirm(data.message)) {
                    Livewire.emit('deleteSelected');
                }
            })
        </script>
    </body>
</html>

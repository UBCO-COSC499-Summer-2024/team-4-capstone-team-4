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
        @vite(['resources/css/app.css', 'resources/css/scrollbar.css', 'resources/css/form.css', 'resources/css/tabs.css', 'resources/css/toolbar.css', 'resources/css/switch.css', 'resources/css/toastify.css','resources/css/course-details.css',
        'resources/css/calendar.css', 'resources/css/card.css', 'resources/css/dropdown.css', 'resources/css/import.css', 'resources/css/svcr.css', 'resources/js/app.js', 'resources/js/tabs.js',
         'resources/js/dropdown.js', 'resources/js/staff.js', 'resources/js/sortTable.js', 'resources/js/buttons.js','resources/js/coursedetails-search.js', 'resources/js/exportReport.js'])

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

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
    <body class="font-sans antialiased">
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
                    ['icon' => 'upload_file', 'href' => '/import', 'title' => 'Import'],
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
        {{-- @toastifyJs --}}
        <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            Livewire.on('show-toast', (data) => {
                // it seems data is an array of objects
                console.log(data);
                data.forEach((toast) => {
                    console.log(toast)
                    Toastify({
                        text: toast.message,
                        selector: document.querySelector('main > .container'),
                        newWindow: toast.destination ? true : false,
                        duration: toast.duration ?? 5000,
                        close: toast.close ?? true,
                        gravity: toast.gravity ?? "bottom",
                        position: toast.position ?? "right",
                        className: toast.className ?? toast.class?? `toastify-${toast.type}`, // Optional styling
                        stopOnFocus: toast.stopOnFocus ??  true,
                        destination: toast.destination ?? null,
                        ariaLive: toast.ariaLive ?? toast.aria ?? "polite", // `assertive` or `off`
                    }).showToast();
                });
            });

            Livewire.on('confirmDelete', (data) => {
                data.forEach((item) => {
                    if (confirm(item.message)) {
                        if (item.model) {
                            switch(item.model) {
                                case 'svcr_item_delete':
                                    Livewire.dispatch('svcr-item-delete', { id: item.id });
                                    break;
                                case 'sr_manage_delete':
                                    Livewire.dispatch('svcr-manage-delete', { id: item.id });
                                    break;
                                case 'staff':
                                    Livewire.dispatch('deleteStaff', { id: item.id });
                                    break;
                                case 'area':
                                    Livewire.dispatch('deleteArea', { id: item.id });
                                    break;
                                case 'role':
                                    Livewire.dispatch('deleteRole', { id: item.id });
                                    break;
                                case 'user':
                                    Livewire.dispatch('deleteUser', { id: item.id });
                                    break;
                                case 'sr_role_assignment':
                                    Livewire.dispatch('sr-remove-instructor', { id: item.id });
                                    break;
                                default:
                                    console.log('Model not found');
                            }
                        } else {
                            Livewire.dispatch('deleteServiceRole', { id: item.serviceRoleId});
                        }
                    }
                })
            });

            Livewire.on('confirmArchive', (data) => {
                data.forEach((item) => {
                    if (confirm(item.message)) {
                        switch(item.model) {
                            case 'svcr_item_archive':
                                Livewire.dispatch('svcr-item-archive', { id: item.id });
                                break;
                            case 'svcr_item_unarchive':
                                Livewire.dispatch('svcr-item-unarchive', { id: item.id });
                                break;
                            case 'sr_manage_archive':
                                Livewire.dispatch('svcr-manage-archive', { id: item.id });
                                break;
                            case 'sr_manage_unarchive':
                                Livewire.dispatch('svcr-manage-unarchive', { id: item.id });
                                break;
                            case 'staff':
                                Livewire.dispatch('archiveStaff', { id: item.id });
                                break;
                            case 'area':
                                Livewire.dispatch('archiveArea', { id: item.id });
                                break;
                            case 'role':
                                Livewire.dispatch('archiveRole', { id: item.id });
                                break;
                            case 'user':
                                Livewire.dispatch('archiveUser', { id: item.id });
                                break;
                            case 'sr_role_assignment':
                                Livewire.dispatch('sr-archive-instructor', { id: item.id });
                                break;
                            default:
                                console.log('Model not found');
                        }
                    }
                })
            });

            Livewire.on('batchDeleteServiceRoles', (data) => {
                data = data[0];
                if (confirm(data.message)) {
                    Livewire.dispatch('deleteAllSelected');
                }
            })
        </script>
    </body>
</html>

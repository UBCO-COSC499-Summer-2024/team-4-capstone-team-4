<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Service Role Report | Insight</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.css">

        <!-- Scripts -->
        @vite(['resources/css/var.css', 'resources/css/app.css', 'resources/css/scrollbar.css', 'resources/css/form.css', 'resources/css/tabs.css', 'resources/css/toolbar.css', 'resources/css/switch.css', 'resources/css/toastify.css','resources/css/course-details.css',
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
    <body class="flex flex-col items-start justify-center font-sans antialiased w-dvw h-dvw">
        <header class="max-w-5xl bg-white shadow-sm">
            <h1>Service Role Report</h1>
        </header>
        <main>
            <div class="hero">
                {{-- basic info --}}
                <div class="basic">
                    <p>
                        <span class="left">ID:</span>
                        <span class="right">{{$serviceRoles->first()->id}}</span>
                    </p>
                    <p>
                        <span class="left">Name:</span>
                        <span class="right">{{ $serviceRoles->first()->name }}</span>
                    </p>
                    {{-- year --}}
                    <p>
                        <span class="left">Year:</span>
                        <span class="right">{{ $serviceRoles->first()->year }}</span>
                    </p>
                    <p>
                        <span class="left">Description:</span>
                        <span class="right">{{ $serviceRoles->first()->description }}</span>
                    </p>
                </div>
            </div>
            <div class="extras">
                {{-- monthly hours --}}
                <section class="monthly-hours">
                    <h2>Monthly Hours</h2>
                    <canvas id="monthly-hours"></canvas>
                    @push('scripts')
                    <script>
                        var ctx = document.getElementById('monthly-hours').getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: [
                                    @foreach ($serviceRoles->first()->monthly_hours as $month => $hour)
                                        '{{ $month }}',
                                    @endforeach
                                ],
                                datasets: [{
                                    label: 'Hours',
                                    data: [
                                        @foreach ($serviceRoles->first()->monthly_hours as $month => $hour)
                                            {{ $hour }},
                                        @endforeach
                                    ],
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(153, 102, 255, 0.2)',
                                        'rgba(255, 159, 64, 0.2)',
                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)',
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    @endpush
                </section>
                {{-- area details --}}
                <section class="area">
                    <h2>Area Details</h2>
                    <table class="details-table">
                        <thead>
                            <tr>
                                <th>Area ID</th>
                                <th>Area Name</th>
                                <th>Area Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $serviceRoles->first()->area->id }}</td>
                                <td>{{ $serviceRoles->first()->area->name }}</td>
                                <td>{{ $serviceRoles->first()->area->description }}</td>
                            </tr>
                        </tbody>
                    </table>
                </section>
                {{-- instructors details --}}
                <section class="instructors">
                    <h2>Instructors Details</h2>
                    <table class="details-table">
                        <thead>
                            <tr>
                                <th>Instructor ID</th>
                                <th>Instructor Name</th>
                                <th>Instructor Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($serviceRoles->first()->instructors as $instructor)
                            <tr>
                                <td>{{ $instructor->id }}</td>
                                <td>{{ $instructor->name }}</td>
                                <td>{{ $instructor->email }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
                {{-- extra hours details --}}
                <section class="extra-hours">
                    <h2>Extra Hours Details</h2>
                    <table class="details-table">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($serviceRoles->first()->extraHours as $extraHour)
                            <tr>
                                <td>{{ $extraHour->month }}</td>
                                <td>{{ $extraHour->year }}</td>
                                <td>{{ $extraHour->hours }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            </div>
        </main>
        <footer class="fixed bottom-0 flex flex-col items-center justify-between mx-auto">
            <p>Page 1 of 1</p>
            <div class="copyright">
                <p>&copy; {{date('Y')}} {{config('app.name', 'Insight')}}</p>
            </div>
        </footer>
        @livewireScripts
    </body>
</html>

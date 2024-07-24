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
        <!-- Scripts -->
        @vite([
            'resources/css/var.css', 'resources/css/app.css', 'resources/css/form.css', 'resources/css/tabs.css', 'resources/css/toolbar.css',
            'resources/css/switch.css', 'resources/css/calendar.css', 'resources/css/card.css', 'resources/css/svcr.css', 'resources/js/app.js', 'resources/js/darkmode.js'
        ])

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- JSPDF Library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

        <!-- Chart.js Library -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Styles -->
        @livewireStyles
        <style type="text/css">
            * {
                font-family: 'figtree', sans-serif;
                padding: 0;
                margin: 0;
            }
            body {
                width: 100%;
                height: 100%;
                position: relative;
                padding: 1rem;
                overflow-wrap: break-word;
            }
            header, main {
                height: fit-content;
                justify-content: center;
                align-items: center;
                position: relative;
                padding: 1rem;
            }
            header, main, footer {
                width: 100%;
                max-width: 1500px;
            }
            header {
                /* background-color: var(--primary-color); */
                border-bottom: 1px solid #d1d5db;
                max-height: unset;
                justify-content: flex-start;
                /* z-index: 0; */
                position: relative;
                margin: auto;
            }
            main {
                display: flex;
                flex-direction: column;
                overflow: initial;
                z-index: 0;
                height: auto;
                padding: 12px 0;
                gap: 12px;
                margin: auto;
            }
            .content-title {
                width: fit-content;
                display: flex;
                justify-content: start;
                flex: 1;
                width: 100%;
            }
            .content-title h1 {
                font-size: 1.5rem;
                font-weight: 600;
                flex: 1;
            }
            h2 {
                font-size: 1.25rem;
                font-weight: 600;
                margin-bottom: 1rem;
            }
            .hero {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                margin: 1rem 0;
                padding: 1rem;
                border-radius: 0.5rem;
                width: 100%;
            }
            .basic {
                display: flex;
                justify-content: space-between;
                align-items: start;
                width: 100%;
            }
            .basic > section {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 100%;
                gap: 1rem;
            }
            .basic > section:first-child {
                flex: 1 1 25%;
            }
            .basic > section:last-child {
                flex: 1 1 75%;
            }
            .basic-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                background: #f3f4f6;
                padding: 0.5rem;
                border-radius: 0.5rem;
            }
            .basic > section:last-child .basic-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .top {
                font-weight: 600;
                margin-bottom: 0.5rem;
            }
            .bottom {
                font-weight: 400;
            }
            .left {
                font-weight: 600;
                margin-right: 1rem;
            }
            .right {
                font-weight: 400;
            }
            .right p {
                margin: 0;
            }
            .right p:last-child {
                margin-bottom: 1rem;
            }
            .extras {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 100%;
                gap: 1rem;
            }
            canvas {
                max-height: 500px;
                padding: 1rem;
            }
            .main section {
                width: 100%;
            }
            .details-table {
                width: 100%;
            }
            .details-table th {
                border: 1px solid #d1d5db;
                padding: 0.5rem;
            }
            .details-table td {
                border: 1px solid #d1d5db;
                padding: 0.5rem;
            }
            footer {
                width: 100%;
                height: 75px;
                max-height: 75px;
                padding: 1rem;
                background-color: #f3f4f6;
                border-top: 1px solid #d1d5db;
                position: relative;
                bottom: 0;
                left: 0;
                right: 0;
                margin: 0 auto;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            .footer-el {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
                flex: 1 1 50%;
            }

            .avatar {
                width: 30px;
                height: 30px;
                border-radius: 50%;
            }
            .svcr-list-item-cell-holder {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            .svcr-list-item-cell[data-column="description"] {
                max-width: 200px;
                overflow: hidden;
                white-space: wrap;
            }

            /* Print styles */
            @media print {
                body {
                    font-size: 12pt;
                }
                header, footer {
                    /* display: none; */
                }

                #header-menu {
                    display: none;
                }
                footer {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-pack: justify;
                    -ms-flex-pack: justify;
                    justify-content: space-between;
                    width: 100%;
                    position: fixed;
                    bottom: 0;
                    /* padding-right: 2cm !important; */
                }
                /* header {
                    -webkit-box-pack: justify;
                    -ms-flex-pack: justify;
                    justify-content: space-between;
                    width: 100%;
                    position: fixed;
                    top: 0;
                    padding-right: 2cm !important;
                } */
                main {
                    width: 100%;
                    margin: 0;
                    padding: 0;
                }
                canvas {
                    max-width: 1000px;
                    max-height: unset;
                }
                .extras {
                    page-break-inside: avoid;
                }
                .details-table th, .details-table td {
                    padding: 8px;
                    /* border: 1px solid #000; */
                    border: 1px solid rgba(0,0,0,0.1);
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        @php
            $serviceRoleId = $serviceRoles?->first()->id ?? request()->route('id');
            $serviceRole = \App\Models\ServiceRole::with('area', 'instructors', 'extraHours')->find($serviceRoleId);
        @endphp
        <x-header />
        <main class="main">
            <div class="content-title">
                <h1>Service Role Report: {{$serviceRole->name}}</h1>
            </div>
            <div class="hero glass">
                <div class="flex justify-between basic">
                    <section class="left">
                        <div class="basic-item">
                            <span class="left">ID:</span>
                            <span class="right">{{ $serviceRole->id }}</span>
                        </div>
                        {{-- <div class="basic-item">
                            <span class="left">Name:</span>
                            <span class="right">{{ $serviceRole->name }}</span>
                        </div> --}}
                        <div class="basic-item">
                            <span class="left">Year:</span>
                            <span class="right">{{ $serviceRole->year }}</span>
                        </div>
                    </section>
                    <section class="right">
                        <div class="basic-item">
                            <span class="top">Description:</span>
                            <span class="bottom">{{ $serviceRole->description }}</span>
                        </div>
                    </section>
                </div>
            </div>
            <section class="monthly-hours">
                <h2>Monthly Hours</h2>
                <canvas id="monthly-hours" class="glass"></canvas>
            </section>
            <section class="area">
                <h2>Area Details</h2>
                <table class="svcr-table details-table">
                    <thead>
                        <tr class="svcr-list-header">
                            <th class="svcr-list-header-item">Area ID</th>
                            <th class="svcr-list-header-item">Area Name</th>
                            <th class="svcr-list-header-item">Area Department</th>
                            <th class="svcr-list-header-item">Archived</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="svcr-list-item">
                            <td class="svcr-list-item-cell">{{ $serviceRole->area->id }}</td>
                            <td class="svcr-list-item-cell">{{ $serviceRole->area->name }}</td>
                            <td class="svcr-list-item-cell">{{ $serviceRole->area->department->name }}</td>
                            <td class="svcr-list-item-cell">{{ $serviceRole->area->archived }}</td>

                        </tr>
                    </tbody>
                </table>
            </section>
            {{-- department --}}
            <section class="department">
                <h2>Department Details</h2>
                <table class="svcr-table details-table">
                    <thead>
                        <tr class="svcr-list-header">
                            <th class="svcr-list-header-item">Department ID</th>
                            <th class="svcr-list-header-item">Department Name</th>
                            <th class="svcr-list-header-item">Archived</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="svcr-list-item">
                            <td class="svcr-list-item-cell">{{ $serviceRole->area->department->id }}</td>
                            <td class="svcr-list-item-cell">{{ $serviceRole->area->department->name }}</td>
                            <td class="svcr-list-item-cell">{{ $serviceRole->area->department->archived }}</td>
                        </tr>
                    </tbody>
                </table>
            </section>
            <div class="extras">
                <section class="instructors">
                    <h2>Instructors Details</h2>
                    <table class="svcr-table details-table">
                        <thead>
                            <tr class="svcr-list-header">
                                <th class="svcr-list-header-item">Instructor ID</th>
                                <th class="svcr-list-header-item">Instructor Name</th>
                                <th class="svcr-list-header-item">Instructor Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($serviceRole->instructors as $instructor)
                            <tr class="svcr-list-item">
                                <td class="svcr-list-item-cell">{{ $instructor->id }}</td>
                                <td class="svcr-list-item-cell">
                                    <div class="svcr-list-item-cell-holder">
                                        <img src="{{ $instructor->profile_photo_url }}" alt="{{ $instructor->getName() }}" class="avatar"> {{ $instructor->getName() }}
                                    </div>
                                </td>
                                <td class="svcr-list-item-cell">{{ $instructor->email }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
                <section class="extra-hours">
                    <h2>Extra Hours Details</h2>
                    <table class="svcr-table details-table">
                        <thead>
                            <tr class="svcr-list-header">
                                <th class="svcr-list-header-item">Hours</th>
                                <th class="svcr-list-header-item">Name</th>
                                <th class="svcr-list-header-item">Description</th>
                                <th class="svcr-list-header-item">Month/Year</th>
                                <th class="svcr-list-header-item">Awarded To</th>
                                <th class="svcr-list-header-item">Awarded By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($serviceRole->extraHours as $extraHour)
                            <tr class="svcr-list-item">
                                <td class="svcr-list-item-cell">{{ $extraHour->hours }}</td>
                                <td class="svcr-list-item-cell">{{ $extraHour->name }}</td>
                                <td class="svcr-list-item-cell" data-column="description">{{ $extraHour->description }}</td>
                                <td class="svcr-list-item-cell">{{ date('F', mktime(0, 0, 0, $extraHour->month, 10)) }}/{{ $extraHour->year }}</td>
                                <td class="svcr-list-item-cell">
                                    <div class="svcr-list-item-cell-holder">
                                        <img src="{{ $extraHour->instructor->user->profile_photo_url }}" alt="{{ $extraHour->instructor->user->getName() }}" class="avatar" /> {{ $extraHour->instructor->user->getName() }}
                                    </div>
                                </td>
                                <td class="svcr-list-item-cell">
                                    <div class="svcr-list-item-cell-holder">
                                        <img src="{{ $extraHour->assigner->user->profile_photo_url }}" alt="{{ $extraHour->assigner->user->getName() }}" class="avatar" /> {{ $extraHour->assigner->user->getName() }}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="empty">
                                <td colspan="3">No extra hours found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </section>
            </div>
        </main>
        <footer>
            <div class="pages footer-el">
                <a href="{{ route('svcroles.manage.id', ['id' => $serviceRole->id]) }}" class="btn btn-primary">Back to Service Roles</a>
            </div>
            <div class="flex flex-row items-center justify-end copyright right footer-el">
                &copy; Copyright {{config('app.name', 'Insight')}} {{date('Y')}}
            </div>
        </footer>
        @livewireScripts
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var ctx = document.getElementById('monthly-hours').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [
                            @foreach ($serviceRole->monthly_hours as $month => $hour)
                                '{{ $month }}',
                            @endforeach
                        ],
                        datasets: [{
                            label: 'Hours',
                            data: [
                                @foreach ($serviceRole->monthly_hours as $month => $hour)
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
            });
        </script>
    </body>
</html>

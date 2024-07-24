@vite(['resources/css/performance.css'])

<x-app-layout>
    <div class="content">
        <h1>{{ $name }}'s Dashboard</h1>
            <div class="button-container">
                <x-performance-export-button :id="$performance->instructor_id">
                    View Report
                </x-performance-export-button>
            </div>
            <div class="profile-container">
                Profile Coming Soon...
            </div>
            @if ($hasTarget)
                <section class="dash-top">
                    <x-instructor-target :chart1="$chart1" :chart4="$chart4" :currentMonth="$currentMonth" :ranking="$ranking" :performance="$performance" />
                </section>
            @else 
                <section class="dash-top">
                    <x-instructor-performance :chart1="$chart1" :currentMonth="$currentMonth" :assignmentCount="$assignmentCount" 
                    :ranking="$ranking" :performance="$performance" />
                </section>
            @endif
            <section class="dash-bottom">
                <x-instructor-lists :assignmentCount="$assignmentCount" :chart2="$chart2" :chart3="$chart3"/>
            </section>
    </div>
</x-app-layout>

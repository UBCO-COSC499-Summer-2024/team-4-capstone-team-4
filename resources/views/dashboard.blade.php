@vite(['resources/css/dashboard.css'])

<x-app-layout>
    <div class="content">
        @if (($isDeptHead || $isDeptStaff) && $isInstructor)
            <!-- Department View with Switch -->
            <h1 class="nos content title">{{ __('Department Dashboard') }}</h1>
            <div class="button-container">
                <x-dashboard-button href="{{route('dept-report')}}">
                    View Report
                </x-dashboard-button>
                <x-dashboard-button href="{{ route('dashboard', ['switch' => true]) }}">
                    My Dashboard
                </x-dashboard-button>
            </div>
            <section class="dash-top">
                <x-department-performance :chart1="$chart1" :currentMonth="$currentMonth" :deptAssignmentCount="$deptAssignmentCount"
                :deptPerformance="$deptPerformance" :leaderboard="$leaderboard" />
            </section>
            <section class="dash-bottom">
                <x-department-lists :deptAssignmentCount="$deptAssignmentCount" :chart2="$chart2" :chart3="$chart3" :chart4="$chart4" />
            </section>
        @elseif ($isDeptHead || $isDeptStaff)
            <!-- Department View -->
            <h1>{{ __('Department Dashboard') }}</h1>
            <div class="button-container">
                <x-dashboard-button href="{{route('dept-report')}}">
                    View Report
                </x-dashboard-button>
            </div>
            <section class="dash-top">
                <x-department-performance :chart1="$chart1" :currentMonth="$currentMonth" :deptAssignmentCount="$deptAssignmentCount" 
                :deptPerformance="$deptPerformance" :leaderboard="$leaderboard" />
            </section>
            <section class="dash-bottom">
                <x-department-lists :deptAssignmentCount="$deptAssignmentCount" :chart2="$chart2" :chart3="$chart3" :chart4="$chart4" />
            </section>
        @elseif ($isInstructor)
            <!-- Instructor View -->
            <h1>{{ __('My Dashboard') }}</h1>
            <div class="button-container">
                <x-dashboard-button href="{{ route('instructor-report', ['instructor_id' => $performance->instructor_id]) }}">
                    View Report
                </x-dashboard-button>
                @if ($switch)
                <x-dashboard-button href="{{route('dashboard')}}">
                    Department Dashboard
                </x-dashboard-button>
                @endif
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
        @endif
    </div>
</x-app-layout>



@php
$userRoles = auth()->user()->roles; 
@endphp
        
@if ($userRoles->isEmpty() || (!$isDeptHead && !$isDeptStaff && !$isInstructor))
    <div class="alert alert-danger">
        No valid role assigned to your account. Redirecting...
    </div>
                
    <!-- Redirect using JavaScript after a brief delay -->
    <script>
        setTimeout(function() {
            const logoutForm = document.getElementById('logoutForm');
            if (logoutForm) {
                logoutForm.submit(); // Submit the logout form
            }
        }, 3000); // 3 second redirect delay
    </script>
@else
    <x-app-layout>
        <div class="content">
            <h1>{{ __('Dashboard') }}</h1>
            @if (($isDeptHead || $isDeptStaff) && $isInstructor)
                <!-- Department View with Button -->
                <section class="dash-top">
                    <x-department-performance :chart1="$chart1" :currentMonth="$currentMonth" :deptAssignmentCount="$deptAssignmentCount"
                    :deptPerformance="$deptPerformance" />
                </section>
                <section class="dash-bottom">
                    <x-department-lists :deptAssignmentCount="$deptAssignmentCount" />
                </section>
            @elseif ($isDeptHead || $isDeptStaff)
                <!-- Department View -->
                <section class="dash-top">
                    <x-department-performance :chart1="$chart1" :currentMonth="$currentMonth" :deptAssignmentCount="$deptAssignmentCount" 
                    :deptPerformance="$deptPerformance" />
                </section>
                <section class="dash-bottom">
                    <x-department-lists :deptAssignmentCount="$deptAssignmentCount" />
                </section>
            @elseif ($isInstructor)
                <!-- Instructor View -->
                @if ($hasTarget)
                    <section class="dash-top">
                        <x-instructor-target :chart1="$chart1" :currentMonth="$currentMonth" :performance="$performance" />
                    </section>
                @else 
                    <section class="dash-top">
                        <x-instructor-performance :chart1="$chart1" :chart2="$chart2" :currentMonth="$currentMonth" :assignmentCount="$assignmentCount" 
                        :performance="$performance" />
                    </section>
                @endif
                <section class="dash-bottom">
                    <x-instructor-lists :assignmentCount="$assignmentCount" />
                </section>
            @endif
        </div>
    </x-app-layout>
@endif
<!-- Hidden form for logout -->
<form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display: none;">
    @csrf
</form>



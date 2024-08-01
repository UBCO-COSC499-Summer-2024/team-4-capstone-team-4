@vite(['resources/css/dashboard.css'])

<x-app-layout>
    <div class="content">
        @if (($isDeptHead || $isDeptStaff) && $isInstructor)
            <!-- Department View with Switch -->
            <h1 class="nos content-title">
                @if ($area && $area['id'] != null)
                <span class="content-title-text">{{$areas[0]['name']}} {{$area['name']}} Dashboard</span>
                @else
                <span class="content-title-text">{{$areas[0]['name']}} Department Dashboard</span>
                @endif
                <div class="flex gap-2 right content-title-btn-holder">
                    <div class="dropdown">
                        Select Year:
                        <select id="yearDropdown" name="yearDropdown" class="w-auto min-w-[75px] text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                            @foreach ($deptYears as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="dropdown">
                        Select Area:
                        <select id="areaDropdown" name="areaDropdown" class="w-auto min-w-[75px] text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                            @foreach ($areas as $dept_area)
                                <option value="{{ json_encode($dept_area) }}" {{ isset($area['id']) && $dept_area['id'] == $area['id'] ? 'selected' : '' }}>{{ $dept_area['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-dashboard-button href="{{ route('dashboard', ['switch' => true]) }}">
                        My Dashboard
                    </x-dashboard-button>
                    <x-dashboard-button href="{{route('dept-report')}}">
                        View Report
                    </x-dashboard-button>
                </div>
            </h1>
            <section class="dash-top">
                <x-department-performance :chart1="$chart1" :currentMonth="$currentMonth" :deptAssignmentCount="$deptAssignmentCount"
                :deptPerformance="$deptPerformance" :leaderboard="$leaderboard" />
            </section>
            <section class="dash-bottom">
                @if ($area && $area['id'] != null)
                <x-department-lists :deptAssignmentCount="$deptAssignmentCount" :chart2="$chart2" :chart3="$chart3" :area="$area" />
                @else
                <x-department-lists :deptAssignmentCount="$deptAssignmentCount" :chart2="$chart2" :chart3="$chart3" :chart4="$chart4" :area="$area" />
                @endif
            </section>
        @elseif ($isDeptHead || $isDeptStaff)
            <!-- Department View -->
            <h1 class="nos content-title">
                @if ($area && $area['id'] != null)
                <span class="content-title-text">{{$areas[0]['name']}} {{$area['name']}} Dashboard</span>
                @else
                <span class="content-title-text">{{$areas[0]['name']}} Department Dashboard</span>
                @endif
                <div class="flex gap-2 right content-title-btn-holder">
                    <div class="dropdown">
                        Select Year:
                        <select id="yearDropdown" name="yearDropdown" class="w-auto min-w-[75px] text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                            @foreach ($deptYears as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="dropdown">
                        Select Area:
                        <select id="areaDropdown" name="areaDropdown" class="w-auto min-w-[75px] text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                            @foreach ($areas as $dept_area)
                                <option value="{{ json_encode($dept_area) }}" {{ isset($area['id']) && $dept_area['id'] == $area['id'] ? 'selected' : '' }}>{{ $dept_area['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-dashboard-button href="{{route('dept-report')}}">
                        View Report
                    </x-dashboard-button>
                </div>
            </h1>
            <section class="dash-top">
                <x-department-performance :chart1="$chart1" :currentMonth="$currentMonth" :deptAssignmentCount="$deptAssignmentCount" 
                :deptPerformance="$deptPerformance" :leaderboard="$leaderboard" />
            </section>
            <section class="dash-bottom">
                @if ($area && $area['id'] != null)
                <x-department-lists :deptAssignmentCount="$deptAssignmentCount" :chart2="$chart2" :chart3="$chart3" :area="$area" />
                @else
                <x-department-lists :deptAssignmentCount="$deptAssignmentCount" :chart2="$chart2" :chart3="$chart3" :chart4="$chart4" :area="$area" />
                @endif
            </section>
        @elseif ($isInstructor)
            <!-- Instructor View -->
            <h1 class="nos content-title">
                <span class="content-title-text">My Dashboard</span>
                <div class="flex gap-2 right content-title-btn-holder">
                    <div class="dropdown">
                        Select Year:
                        <select id="yearDropdown" name="yearDropdown" class="w-auto min-w-[75px] text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($switch)
                    <x-dashboard-button href="{{route('dashboard')}}">
                        Department Dashboard
                    </x-dashboard-button>
                    @endif
                    <x-dashboard-button href="{{route('instructor-report', ['instructor_id' => $performance->instructor_id])}}">
                        View Report
                    </x-dashboard-button>
                </div>
            </h1>
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
        @elseif ($isAdmin)
        <!-- Admin View -->
        <h1 class="nos content-title">
            <span class="content-title-text">Administrative Dashboard</span>
        </h1>
        <div class="content">
            <h1 class="content-title">
            </h1>
            <livewire:admin-dashboard />
    <   </div>
        @endif
    </div>
</x-app-layout>
<!-- Handle year/area selection -->
<script>
    document.getElementById('yearDropdown').addEventListener('change', function() {
        var selectedYear = this.value;
        var params = new URLSearchParams(window.location.search);
        params.set('year', selectedYear);
        window.location.search = params.toString();
    });

    document.getElementById('areaDropdown').addEventListener('change', function() {
        var selectedArea = this.value;
        var params = new URLSearchParams(window.location.search);
        params.set('area', selectedArea);
        window.location.search = params.toString();
    });
</script>


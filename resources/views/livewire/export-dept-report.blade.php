<div class="content">
    <h1 class="content-title nos">
        <span class="content-title-text">{{$dept->name}} Department Report</span>
    </h1>
    <div>
        <div class="flex justify-between items-center">
            <div>
                <label for="year">Select Year:</label>
                <select wire:model.live="year" id="year" name="year" class="w-auto min-w-[75px] text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    @php
                        $allPerformances = $dept->departmentPerformance->sortBy('year');
                    @endphp
                    @foreach ($allPerformances as $perf)
                        <option value="{{ $perf->year }}" {{ $perf->year == $year ? 'selected' : '' }}>{{ $perf->year }}</option>
                    @endforeach
                </select>
            </div>
            <x-report-dropdown/>
        </div>
        <div id="exportDeptContent">
            @if ($areas->isNotEmpty())
                <h2 class="font-bold">Courses Performance</h2>
                <h3>Summary</h3>
                <table id="courseTable" class="w-full bg-white border border-gray-300 text-center">
                    <thead>    
                        <tr class="text-white bg-[#3b4779]">
                            <th>Sub area</th>
                            <th>Num. of Instructors</th>
                            <th>Num. of Course Sections</th>
                            <th>Enrolled (%)</th>
                            <th>Dropped (%)</th>
                            <th>SEI Average (IM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalInstructors = 0;
                            $totalCourses = 0;
                        @endphp
                        @foreach ($areas as $area)
                            @php
                                $areaPerformance = $area->areaPerformance->where('year', $year)->first();
                                $courses = \App\Models\Area::getCourseSections($area->id, $year);
                                $instructors = \App\Models\Area::getInstructors($area->id, $year);
                            @endphp
                            @if($courses->isNotEmpty())
                                @php
                                    $numInstructors = $instructors ? count($instructors) : 0;
                                    $numCourses = $courses ? count($courses) : 0;
                                    $totalInstructors += $numInstructors;
                                    $totalCourses += $numCourses;
                                @endphp
                                <tr>
                                    <td class="border border-gray-300">{{$area->name}}</td>
                                    <td class="border border-gray-300">{{ $numInstructors }}</td>
                                    <td class="border border-gray-300">{{ $numCourses }}</td>
                                    <td class="border border-gray-300">{{ $areaPerformance ? $areaPerformance->enrolled_avg : '-'}}</td>
                                    <td class="border border-gray-300">{{ $areaPerformance ? $areaPerformance->dropped_avg : '-'}}</td>
                                    <td class="border border-gray-300">{{ $areaPerformance ? $areaPerformance->sei_avg : '-'}}</td>
                                </tr>
                            @endif
                        @endforeach
                        @if ($deptPerformance)
                            <tr class="font-bold bg-gray-400">
                                <td class="border border-gray-300">Total</td>
                                <td class="border border-gray-300">{{ $totalInstructors }}</td>
                                <td class="border border-gray-300">{{ $totalCourses }}</td>
                                <td class="border border-gray-300">{{ $deptPerformance->enrolled_avg }}</td>
                                <td class="border border-gray-300">{{ $deptPerformance->dropped_avg }}</td>
                                <td class="border border-gray-300">{{ $deptPerformance->sei_avg }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <br>

                @foreach($areas as $area)
                    <h3>{{$area->name}}</h3>
                    <table class="areaCourseTable w-full bg-white border border-gray-300 text-center">
                        <thead>
                            <tr class="text-white bg-[#3b4779]">
                                <th>Course Section</th>
                                <th>Term</th>
                                <th>Year</th>
                                <th>Instructor</th>
                                <th>Enrolled (%)</th>
                                <th>Dropped (%)</th>
                                <th>SEI Average (IM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $courses = \App\Models\Area::getCourseSections($area->id, $year);
                                $areaPerformance = $area->areaPerformance->where('year', $year)->first();
                            @endphp
                            @foreach ($courses as $course)
                                @php
                                    $sei = \App\Models\SeiData::calculateSEIAverage($course->id);
                                    $capacity = $course->capacity;
                                    $instructor = \App\Models\UserRole::find($course->teaches->instructor_id);
                                @endphp
                                <tr>
                                    <td class="border border-gray-300">{{ $course->prefix }}{{ $course->number }}  {{ $course->section }}</td>
                                    <td class="border border-gray-300">Term {{ $course->term }}</td>
                                    <td class="border border-gray-300">{{ $course->year }}</td>
                                    <td class="border border-gray-300">{{ $instructor->user->firstname }} {{ $instructor->user->lastname }}</td>
                                    <td class="border border-gray-300">{{ round($course->enrolled * 100 / $capacity, 1) }}</td>
                                    <td class="border border-gray-300">{{ round($course->dropped * 100 / $capacity, 1) }}</td>
                                    <td class="border border-gray-300">{{ $sei ? $sei : '-' }}</td>
                                </tr>
                            @endforeach
                            @if ($areaPerformance)
                                <tr class="font-bold bg-gray-400">
                                    <td class="border border-gray-300" colspan="4">Total</td>
                                    <td class="border border-gray-300">{{ $areaPerformance ? $areaPerformance->enrolled_avg : '-'}}</td>
                                    <td class="border border-gray-300">{{ $areaPerformance ? $areaPerformance->dropped_avg : '-'}}</td>
                                    <td class="border border-gray-300">{{ $areaPerformance ? $areaPerformance->sei_avg : '-'}}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <br>
                @endforeach

                <br>

                <h2 class="font-bold">Service Roles & Extra Hours Performance</h2>
                <h3>Summary</h3>
                <table id="performanceTable" class="w-full bg-white border border-gray-300 text-center">
                    <thead>
                        @php
                            $deptHours = json_decode($deptPerformance->total_hours, true);
                            $totalSvcroles = 0;
                            $totalExtraHours = 0;
                        @endphp
                        <tr class="text-white bg-[#3b4779]">
                            <th>Sub area</th>
                            <th>Num. of Service Roles</th>
                            <th>Num. of Extra Hours</th>
                            @foreach ($deptHours as $month => $hours)
                                <th>{{ substr($month, 0, 3) }} Hours</th>
                            @endforeach
                            <th>Total Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($areas as $area)
                            @php
                                $areaPerformance = $area->areaPerformance->where('year', $year)->first();
                                $svcroles = \App\Models\Area::getServiceRoles($area->id, $year);
                                $extraHours = \App\Models\Area::getExtraHours($area->id, $year);
                                $areaHours = json_decode($areaPerformance->total_hours, true);
                            @endphp
                            @if ($svcroles->isNotEmpty() || $extraHours->isNotEmpty())
                                @php
                                    $numSvcroles = $svcroles ? count($svcroles) : 0;
                                    $numExtraHours = $extraHours ? count($extraHours) : 0;
                                    $totalSvcroles += $numSvcroles;
                                    $totalExtraHours += $numExtraHours;
                                @endphp
                                <tr>
                                    <td class="border border-gray-300">{{$area->name}}</td>
                                    <td class="border border-gray-300">{{ $numSvcroles }}</td>
                                    <td class="border border-gray-300">{{ $numExtraHours }}</td>
                                    @foreach ($areaHours as $month => $hours)
                                        <td class="border border-gray-300">{{ $hours }}</td>
                                    @endforeach
                                    <td class="border border-gray-300">{{ array_sum($areaHours) }}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tr class="border-b border-white font-bold bg-gray-400">
                            <td class="border border-gray-300">Total</td>
                            <td class="border border-gray-300">{{ $totalSvcroles }}</td>
                            <td class="border border-gray-300">{{ $totalExtraHours }}</td>
                            @foreach ($deptHours as $month => $hours)
                                <td class="border border-gray-300">{{ $hours }}</td>   
                            @endforeach
                            <td class="border border-gray-300">{{ array_sum($deptHours) }}</td>
                        </tr> 
                    </tbody>
                </table>

                <br>

                @foreach ($areas as $area)
                    @php
                        $svcroles = \App\Models\Area::getServiceRoles($area->id, $year);
                        $extraHours = \App\Models\Area::getExtraHours($area->id, $year);
                        $areaHours = json_decode($areaPerformance->total_hours, true);
                    @endphp
                    @if ($svcroles->isNotEmpty() || $extraHours->isNotEmpty())   
                        <h3>{{ $area->name }}</h3>
                    @endif
                    <table class="areaPerfTable w-full bg-white border border-gray-300 text-center">
                        @if ($svcroles->isNotEmpty())
                            <thead>
                                <tr class="text-white bg-[#3b4779]">
                                    <th>Service Role</th>
                                    <th>Instructors</th>
                                    @foreach ($areaHours as $month => $hours)
                                        <th>{{ substr($month, 0, 3) }} Hours</th>
                                    @endforeach
                                    <th>Total Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($svcroles as $svcRole)
                                    <tr>
                                        <td class="border border-gray-300">{{$svcRole->name}}</td>
                                        <td class="border border-gray-300">
                                            @php
                                                $instructors = $svcRole->instructors;
                                            @endphp
                                            @if ($instructors)
                                                @foreach ($instructors as $instructor)
                                                    {{ $instructor->firstname }} {{ $instructor->lastname }}<br>
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @foreach ($areaHours as $month => $hours)
                                            <td class="border border-gray-300">{{ $hours }}</td>
                                        @endforeach
                                        <td class="border border-gray-300">{{ array_sum($areaHours)}}</td>
                                    </tr>
                                @endforeach
                        @endif

                        @if ($extraHours->isNotEmpty())
                                <tr class="text-white bg-[#3b4779]">
                                    <th colspan="4">Extra Hour</th>
                                    <th colspan="4">Instructor</th>
                                    <th colspan="4">Month</th>
                                    <th colspan="3">Hours</th>
                                </tr>
                                @foreach ($extraHours as $extraHour)
                                    <tr>
                                        <td colspan="4" class="border border-gray-300">{{$extraHour->name}}</td>
                                        <td colspan="4" class="border border-gray-300">
                                            @php
                                                $instructor = $extraHour->instructor;
                                            @endphp
                                            {{ $instructor->user->firstname }} {{ $instructor->user->lastname }}
                                        </td>
                                        <td colspan="4" class="border border-gray-300">{{ \DateTime::createFromFormat('!m', $extraHour->month)->format('F') }}</td>
                                        <td colspan="3" class="border border-gray-300">{{$extraHour->hours}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                    <br>
                @endforeach
            @endif
        </div>
    </div>
</div>

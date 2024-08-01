<div class="content">
    <h1 class="content-title nos">
        <span class="content-title-text">{{$dept->name}} Department Report</span>
    </h1>
    <div>
        <div class="flex justify-end items-center">
            <div class="mr-2">
                <label for="year">Select Year:</label>
                <select wire:model.live="year" id="year" name="year" class="w-auto min-w-[75px] text-gray-500 bg-white report-cell focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
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
                <table id="courseTable" class="report-table">
                    <thead>    
                        <tr class="text-white bg-[#3b4779]">
                            <th class="report-head-cell">Sub area</th>
                            <th class="report-head-cell">No. of Instructors</th>
                            <th class="report-head-cell">No. of Course Sections</th>
                            <th class="report-head-cell">Enrolled (%)</th>
                            <th class="report-head-cell">Dropped (%)</th>
                            <th class="report-head-cell">SEI Average (IM)</th>
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
                          
                                $numInstructors = $instructors ? count($instructors) : 0;
                                $numCourses = $courses ? count($courses) : 0;
                                $totalInstructors += $numInstructors;
                                $totalCourses += $numCourses;
                                @endphp
                                <tr>
                                    <td class="report-cell">{{$area->name}}</td>
                                    <td class="report-cell">{{ $numInstructors }}</td>
                                    <td class="report-cell">{{ $numCourses }}</td>
                                    <td class="report-cell">{{ $areaPerformance ? $areaPerformance->enrolled_avg : '-'}}</td>
                                    <td class="report-cell">{{ $areaPerformance ? $areaPerformance->dropped_avg : '-'}}</td>
                                    <td class="report-cell">{{ $areaPerformance ? $areaPerformance->sei_avg : '-'}}</td>
                                </tr>
                        @endforeach
                            <tr class="total-row">
                                <td class="report-cell">Total</td>
                                <td class="report-cell">{{ $totalInstructors }}</td>
                                <td class="report-cell">{{ $totalCourses }}</td>
                                <td class="report-cell">{{ $deptPerformance ? $deptPerformance->enrolled_avg : '-'}}</td>
                                <td class="report-cell">{{ $deptPerformance ? $deptPerformance->dropped_avg : '-'}}</td>
                                <td class="report-cell">{{ $deptPerformance ? $deptPerformance->sei_avg : '-' }}</td>
                            </tr>
                    </tbody>
                </table>

                <br>

                @foreach($areas as $area)
                    @php
                        $courses = \App\Models\Area::getCourseSections($area->id, $year);
                        $areaPerformance = $area->areaPerformance->where('year', $year)->first();
                    @endphp
                    @if($courses->isNotEmpty())
                        <h3> {{$area->name}} </h3>
                        <table class="areaCourseTable report-table">
                            <thead>
                                <tr class="text-white bg-[#3b4779]">
                                    <th class="report-head-cell">Course Section</th>
                                    <th class="report-head-cell">Term</th>
                                    <th class="report-head-cell">Year</th>
                                    <th class="report-head-cell">Instructor</th>
                                    <th class="report-head-cell">Enrolled (%)</th>
                                    <th class="report-head-cell">Dropped (%)</th>
                                    <th class="report-head-cell">SEI Average (IM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $course)
                                    @php
                                        $sei = \App\Models\SeiData::calculateSEIAverage($course->id);
                                        $capacity = $course->capacity;
                                        $instructor = \App\Models\UserRole::find($course->teaches->instructor_id);
                                    @endphp
                                    <tr>
                                        <td class="report-cell">{{ $course->prefix }}{{ $course->number }}  {{ $course->section }}</td>
                                        <td class="report-cell">Term {{ $course->term }}</td>
                                        <td class="report-cell">{{ $course->year }}</td>
                                        <td class="report-cell">{{ $instructor->user->firstname }} {{ $instructor->user->lastname }}</td>
                                        <td class="report-cell">{{ round($course->enroll_end * 100 / $capacity, 1) }}</td>
                                        <td class="report-cell">{{ round($course->dropped * 100 / $capacity, 1) }}</td>
                                        <td class="report-cell">{{ $sei ? $sei : '-' }}</td>
                                    </tr>
                                @endforeach
                                    <tr class="total-row">
                                        <td class="report-cell" colspan="4">Total</td>
                                        <td class="report-cell">{{ $areaPerformance ? $areaPerformance->enrolled_avg : '-'}}</td>
                                        <td class="report-cell">{{ $areaPerformance ? $areaPerformance->dropped_avg : '-'}}</td>
                                        <td class="report-cell">{{ $areaPerformance ? $areaPerformance->sei_avg : '-'}}</td>
                                    </tr>
                            </tbody>
                        </table>
                        <br>
                    @endif
                @endforeach

                <br>

                <h2 class="font-bold">Service Roles & Extra Hours Performance</h2>
                <h3>Summary</h3>
                <table id="performanceTable" class="report-table">
                    <thead>
                        @php
                        if($deptPerformance){
                            $deptHours = json_decode($deptPerformance->total_hours, true);
                        }else{
                            $deptHours = [];
                        }
                            $totalSvcroles = 0;
                            $totalExtraHours = 0;
                        @endphp
                        <tr class="text-white bg-[#3b4779]">
                            <th class="report-head-cell">Sub area</th>
                            <th class="report-head-cell">No. of Service Roles</th>
                            <th class="report-head-cell">No. of Extra Hrs</th>
                            @if(!empty($deptHours))
                                @foreach ($deptHours as $month => $hours)
                                    <th class="report-head-cell">{{ substr($month, 0, 3) }}</th>
                                @endforeach
                            @else
                                @for ($i = 1; $i <= 12; $i++)
                                    <th class="report-head-cell">{{ substr(DateTime::createFromFormat('!m', $i)->format('F'), 0, 3) }} Hrs </th>
                                @endfor
                            @endif
                            <th class="report-head-cell">Total Hrs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($areas as $area)
                            @php
                                $areaPerformance = $area->areaPerformance->where('year', $year)->first();
                                $svcroles = \App\Models\Area::getServiceRoles($area->id, $year);
                                $extraHours = \App\Models\Area::getExtraHours($area->id, $year);
                                if($areaPerformance){
                                    $areaHours = json_decode($areaPerformance->total_hours, true);
                                }else{
                                    $areaHours = [];
                                }
                              
                                $numSvcroles = $svcroles ? count($svcroles) : 0;
                                $numExtraHours = $extraHours ? count($extraHours) : 0;
                                $totalSvcroles += $numSvcroles;
                                $totalExtraHours += $numExtraHours;
                            @endphp
                            <tr>
                                <td class="report-cell">{{$area->name}}</td>
                                <td class="report-cell">{{ $numSvcroles }}</td>
                                <td class="report-cell">{{ $numExtraHours }}</td>
                                @if(!empty($areaHours))
                                    @foreach ($areaHours as $month => $hours)
                                        <td class="report-cell">{{ $hours }}</td>
                                    @endforeach
                                @else
                                    @for ($i = 1; $i <= 12; $i++)
                                        <td class="report-cell">0</td>
                                    @endfor
                                @endif
                                <td class="report-cell">{{ array_sum($areaHours) }}</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td class="report-cell">Total</td>
                            <td class="report-cell">{{ $totalSvcroles }}</td>
                            <td class="report-cell">{{ $totalExtraHours }}</td>
                            @if(!empty($areaHours))
                                @foreach ($deptHours as $month => $hours)
                                    <td class="report-cell">{{ $hours }}</td>
                                @endforeach
                            @else
                                @for ($i = 1; $i <= 12; $i++)
                                    <td class="report-cell">-</td>
                                @endfor
                            @endif
                            <td class="report-cell">{{ array_sum($deptHours) }}</td>
                        </tr> 
                    </tbody>
                </table>

                <br>

                @foreach ($areas as $area)
                    @php
                        $svcroles = \App\Models\Area::getServiceRoles($area->id, $year);
                        $extraHours = \App\Models\Area::getExtraHours($area->id, $year);
                    @endphp
                    @if ($svcroles->isNotEmpty() || $extraHours->isNotEmpty())   
                        <h3>{{ $area->name }}</h3>
                    <table class="areaPerfTable report-table">
                        @if ($svcroles->isNotEmpty())
                            <p><i>Note: The monthly hours is the sum of the total hours worked by each instructor in that service role.</i></p>
                            <thead>
                                <tr class="text-white bg-[#3b4779]">
                                    <th class="report-head-cell">Service Role</th>
                                    <th class="report-head-cell">Instructors</th>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <th class="report-head-cell">{{ substr(DateTime::createFromFormat('!m', $i)->format('F'), 0, 3) }}</th>
                                    @endfor
                                    <th class="report-head-cell">Total Hrs</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($svcroles as $svcRole)
                                    <tr>
                                        <td class="report-cell">{{$svcRole->name}}</td>
                                        <td class="report-cell">
                                            @php
                                                $instructors = $svcRole->instructors;
                                            @endphp
                                            @if ($instructors)
                                                {{ $instructors->pluck('firstname', 'lastname')->map(function($lastname, $firstname) {
                                                    return $firstname . ' ' . $lastname;
                                                })->implode(', ') }}                                            
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @php
                                            $svcHours = $svcRole->monthly_hours;
                                        @endphp
                                        @if(!empty($svcHours))
                                            @foreach ($svcHours as $month => $hours)
                                                <td class="report-cell">{{ $hours * count($instructors)}}</td>
                                            @endforeach
                                        @else
                                            @for ($i = 1; $i <= 12; $i++)
                                                <td class="report-cell">-</td>
                                            @endfor
                                        @endif
                                        <td class="report-cell">{{ array_sum($svcHours) * count($instructors)}}</td>
                                    </tr>
                                @endforeach
                        @endif

                        @if ($extraHours->isNotEmpty())
                                <tr class="text-white bg-[#3b4779]">
                                    <th class="report-head-cell" colspan="4">Extra Hour</th>
                                    <th class="report-head-cell" colspan="4">Instructor</th>
                                    <th class="report-head-cell" colspan="4">Month</th>
                                    <th class="report-head-cell" colspan="3">Hours</th>
                                </tr>
                                @foreach ($extraHours as $extraHour)
                                    <tr>
                                        <td colspan="4" class="report-cell">{{$extraHour->name}}</td>
                                        <td colspan="4" class="report-cell">
                                            @php
                                                $instructor = $extraHour->instructor;
                                            @endphp
                                            {{ $instructor->user->firstname }} {{ $instructor->user->lastname }}
                                        </td>
                                        <td colspan="4" class="report-cell">{{ \DateTime::createFromFormat('!m', $extraHour->month)->format('F') }}</td>
                                        <td colspan="3" class="report-cell">{{$extraHour->hours}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                    @endif
                    <br>
                @endforeach
            @endif
        </div>
    </div>
</div>

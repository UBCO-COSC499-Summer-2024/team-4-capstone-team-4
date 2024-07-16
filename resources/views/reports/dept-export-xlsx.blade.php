<table>
    <thead>
        <tr>
            <th style="font-weight:bold; font-size:18px;">
                {{$dept->name}} Department Report
            </th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

@if ($areas->isNotEmpty())
    <p>Courses Performance</p>
    <p>Summary</p>
    <table>
        <thead>    
            <tr>
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
                        <td>{{$area->name}}</td>
                        <td>{{ $numInstructors }}</td>
                        <td>{{ $numCourses }}</td>
                        <td>{{ $areaPerformance ? $areaPerformance->enrolled_avg : '-'}}</td>
                        <td>{{ $areaPerformance ? $areaPerformance->dropped_avg : '-'}}</td>
                        <td>{{ $areaPerformance ? $areaPerformance->sei_avg : '-'}}</td>
                    </tr>
                @endif
            @endforeach
            @if ($deptPerformance)
                <tr>
                    <td>Total</td>
                    <td>{{ $totalInstructors }}</td>
                    <td>{{ $totalCourses }}</td>
                    <td>{{ $deptPerformance->enrolled_avg }}</td>
                    <td>{{ $deptPerformance->dropped_avg }}</td>
                    <td>{{ $deptPerformance->sei_avg }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <br>

    @foreach($areas as $area)
        <p>{{$area->name}}</p>
        <table>
            <thead>
                <tr>
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
                        <td>{{ $course->prefix }}{{ $course->number }}  {{ $course->section }}</td>
                        <td>Term {{ $course->term }}</td>
                        <td>{{ $course->year }}</td>
                        <td>{{ $instructor->user->firstname }} {{ $instructor->user->lastname }}</td>
                        <td>{{ round($course->enrolled * 100 / $capacity, 1) }}</td>
                        <td>{{ round($course->dropped * 100 / $capacity, 1) }}</td>
                        <td>{{ $sei ? $sei : '-' }}</td>
                    </tr>
                @endforeach
                @if ($areaPerformance)
                    <tr>
                        <td colspan="4">Total</td>
                        <td>{{ $areaPerformance ? $areaPerformance->enrolled_avg : '-'}}</td>
                        <td>{{ $areaPerformance ? $areaPerformance->dropped_avg : '-'}}</td>
                        <td>{{ $areaPerformance ? $areaPerformance->sei_avg : '-'}}</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <br>
    @endforeach

    <br>

    <p>Service Roles & Extra Hours Performance</p>
    <p>Summary</p>
    <table>
        <thead>
            @php
                $deptHours = json_decode($deptPerformance->total_hours, true);
                $totalSvcroles = 0;
                $totalExtraHours = 0;
            @endphp
            <tr>
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
                        <td>{{$area->name}}</td>
                        <td>{{ $numSvcroles }}</td>
                        <td>{{ $numExtraHours }}</td>
                        @foreach ($areaHours as $month => $hours)
                            <td>{{ $hours }}</td>
                        @endforeach
                        <td>{{ array_sum($areaHours) }}</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td>Total</td>
                <td>{{ $totalSvcroles }}</td>
                <td>{{ $totalExtraHours }}</td>
                @foreach ($deptHours as $month => $hours)
                    <td>{{ $hours }}</td>   
                @endforeach
                <td>{{ array_sum($deptHours) }}</td>
            </tr> 
        </tbody>
    </table>

    <br>

    @foreach ($areas as $area)
        <p>{{ $area->name }}</p>
        @php
            $svcroles = \App\Models\Area::getServiceRoles($area->id, $year);
            $extraHours = \App\Models\Area::getExtraHours($area->id, $year);
            $areaHours = json_decode($areaPerformance->total_hours, true);
        @endphp
        <table>
            @if ($svcroles->isNotEmpty())
                <thead>
                    <tr>
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
                            <td>{{$svcRole->name}}</td>
                            <td>
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
                                <td>{{ $hours }}</td>
                            @endforeach
                            <td>{{ array_sum($areaHours)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif

            @if ($extraHours->isNotEmpty())
                <thead>
                    <tr>
                        <th colspan="4">Extra Hour</th>
                        <th colspan="4">Instructor</th>
                        <th colspan="4">Month</th>
                        <th colspan="3">Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($extraHours as $extraHour)
                        <tr>
                            <td colspan="4">{{$extraHour->name}}</td>
                            <td colspan="4">
                                @php
                                    $instructor = $extraHour->instructor;
                                @endphp
                                {{ $instructor->user->firstname }} {{ $instructor->user->lastname }}<br>
                            </td>
                            <td colspan="4">{{ \DateTime::createFromFormat('!m', $extraHour->month)->format('F') }}</td>
                            <td colspan="3">{{$extraHour->hours}}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
        <br>
    @endforeach
@endif

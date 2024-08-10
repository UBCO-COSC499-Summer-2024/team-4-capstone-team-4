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

<table>
    <thead>
        <tr>
            <th style="font-weight:bold; font-size:15px;">
                Course Performance
            </th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

@if ($areas->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th style="font-weight:bold;">Summary</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="font-weight:bold;">Sub area</th>
                <th style="font-weight:bold;">Num. of Instructors</th>
                <th style="font-weight:bold;">Num. of Course Sections</th>
                <th style="font-weight:bold;">Enrolled (%)</th>
                <th style="font-weight:bold;">Dropped (%)</th>
                <th style="font-weight:bold;">SEI Average (IM)</th>
            </tr>
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
                    <td>{{$area->name}}</td>
                    <td>{{ $numInstructors }}</td>
                    <td>{{ $numCourses }}</td>
                    <td>{{ $areaPerformance ? $areaPerformance->enrolled_avg : '-' }}</td>
                    <td>{{ $areaPerformance ? $areaPerformance->dropped_avg : '-' }}</td>
                    <td>{{ $areaPerformance ? $areaPerformance->sei_avg : '-' }}</td>
                </tr>
            @endforeach
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>{{ $totalInstructors }}</strong></td>
                <td><strong>{{ $totalCourses }}</strong></td>
                <td><strong>{{ $deptPerformance ? $deptPerformance->enrolled_avg : '-' }}</strong></td>
                <td><strong>{{ $deptPerformance ? $deptPerformance->dropped_avg : '-' }}</strong></td>
                <td><strong>{{ $deptPerformance ? $deptPerformance->sei_avg : '-' }}</strong></td>
            </tr>
        </tbody>
    </table>
    
    @foreach($areas as $area)
        @php
            $courses = \App\Models\Area::getCourseSections($area->id, $year);
            $areaPerformance = $area->areaPerformance->where('year', $year)->first();
        @endphp
        @if($courses->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th colspan="9" style="font-weight:bold;">{{$area->name}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th style="font-weight:bold;">Course Section</th>
                        <th style="font-weight:bold;">Term</th>
                        <th style="font-weight:bold;">Year</th>
                        <th style="font-weight:bold;">Room</th>
                        <th style="font-weight:bold;">Times</th>
                        <th style="font-weight:bold;">Instructor</th>
                        <th style="font-weight:bold;">Enrolled (%)</th>
                        <th style="font-weight:bold;">Dropped (%)</th>
                        <th style="font-weight:bold;">SEI Average (IM)</th>
                    </tr>
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
                            <td>{{ $course->room }}</td>
                            <td>{{ $course->time_start }} - {{ $course->time_end }}</td>
                            <td>{{ $instructor ? $instructor->user->firstname : '-' }} {{ $instructor ? $instructor->user->lastname : '-' }}</td>
                            <td>{{ round($course->enroll_end * 100 / $capacity, 1) }}</td>
                            <td>{{ round($course->dropped * 100 / $capacity, 1) }}</td>
                            <td>{{ $sei ? $sei : '-' }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="6"><strong>Total</strong></td>
                        <td><strong>{{ $areaPerformance ? $areaPerformance->enrolled_avg : '-' }}</strong></td>
                        <td><strong>{{ $areaPerformance ? $areaPerformance->dropped_avg : '-' }}</strong></td>
                        <td><strong>{{ $areaPerformance ? $areaPerformance->sei_avg : '-' }}</strong></td>
                    </tr>
                </tbody>
            </table>
            <br>
        @endif
    @endforeach

    <table>
        <thead></thead>
        <tbody>
            <tr>
                <th style="font-weight:bold; font-size:15px;">
                    Service Roles and Extra Hours Performance
                </th>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th style="font-weight:bold;">Summary</th>
            </tr>
            @php
            if($deptPerformance){
                $deptHours = json_decode($deptPerformance->total_hours, true);
            }else{
                $deptHours = [];
            }
                $totalSvcroles = 0;
                $totalExtraHours = 0;
            @endphp
        </thead>
        <tbody>
            <tr>
                <th style="font-weight:bold;">Sub area</th>
                <th style="font-weight:bold;">No. of Service Roles</th>
                <th style="font-weight:bold;">No. of Extra Hrs</th>
                @if(!empty($deptHours))
                    @foreach ($deptHours as $month => $hours)
                        <th style="font-weight:bold;">{{ substr($month, 0, 3) }}</th>
                    @endforeach
                @else
                    @for ($i = 1; $i <= 12; $i++)
                        <th style="font-weight:bold;">{{ substr(DateTime::createFromFormat('!m', $i)->format('F'), 0, 3) }}</th>
                    @endfor
                @endif
                <th style="font-weight:bold;">Total Hrs</th>
            </tr>
            @foreach ($areas as $area)
                @php
                    $areaPerformance = $area->areaPerformance->where('year', $year)->first();
                    $svcroles = \App\Models\Area::getServiceRoles($area->id, $year);
                    $extraHours = \App\Models\Area::getExtraHours($area->id, $year);
                    $areaHours = $areaPerformance ? json_decode($areaPerformance->total_hours, true) : [];
                    $numSvcroles = $svcroles ? count($svcroles) : 0;
                    $numExtraHours = $extraHours ? count($extraHours) : 0;
                    $totalSvcroles += $numSvcroles;
                    $totalExtraHours += $numExtraHours;
                @endphp
                <tr>
                    <td>{{$area->name}}</td>
                    <td>{{ $numSvcroles }}</td>
                    <td>{{ $numExtraHours }}</td>
                    @if(!empty($areaHours))
                        @foreach ($areaHours as $month => $hours)
                            <td>{{ $hours }}</td>
                        @endforeach
                    @else
                        @for ($i = 1; $i <= 12; $i++)
                            <td>0</td>
                        @endfor
                    @endif
                    <td>{{ array_sum($areaHours) }}</td>
                </tr>
            @endforeach
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>{{ $totalSvcroles }}</strong></td>
                <td><strong>{{ $totalExtraHours }}</strong></td>
                @if(!empty($areaHours))
                    @foreach ($deptHours as $month => $hours)
                        <td><strong>{{ $hours }}</strong></td>
                    @endforeach
                @else
                    @for ($i = 1; $i <= 12; $i++)
                        <td><strong>-</strong></td>
                    @endfor
                @endif
                <td><strong>{{ array_sum($deptHours) }}</strong></td>
            </tr> 
        </tbody>
    </table>

    @foreach ($areas as $area)
        @php
            $svcroles = \App\Models\Area::getServiceRoles($area->id, $year);
            $extraHours = \App\Models\Area::getExtraHours($area->id, $year);
        @endphp
        @if ($svcroles->isNotEmpty() || $extraHours->isNotEmpty())   
        <table>
            <thead>
                <tr>
                    <th colspan="6" style="font-weight:bold;">{{$area->name}}</th>
                </tr>
            </thead>
            <tbody>
            @if ($svcroles->isNotEmpty())
                <tr><td>Note: The monthly hours is the sum of the total hours worked by each instructor in that service role.</td></tr>
                <tr>
                    <th style="font-weight:bold;">Service Role</th>
                    <th style="font-weight:bold;">Instructors</th>
                    @for ($i = 1; $i <= 12; $i++)
                        <th style="font-weight:bold;">{{ substr(DateTime::createFromFormat('!m', $i)->format('F'), 0, 3) }}</th>
                    @endfor
                    <th style="font-weight:bold;">Total Hrs</th>
                </tr>
                @foreach ($svcroles as $svcRole)
                    <tr>
                        <td>{{$svcRole->name}}</td>
                        <td>
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
                                <td>{{ $hours * count($instructors)}}</td>
                            @endforeach
                        @else
                            @for ($i = 1; $i <= 12; $i++)
                                <td>-</td>
                            @endfor
                        @endif
                        <td>{{ array_sum($svcHours) * count($instructors)}}</td>
                    </tr>
                @endforeach
            @endif

            @if ($extraHours->isNotEmpty())
                <tr>
                    <th style="font-weight:bold;">Extra Hour</th>
                    <th style="font-weight:bold;">Instructor</th>
                    <th style="font-weight:bold;">Month</th>
                    <th style="font-weight:bold;">Hours</th>
                </tr>
                @foreach ($extraHours as $extraHour)
                    <tr>
                        <td>{{$extraHour->name}}</td>
                        <td>
                            @php
                                $instructor = $extraHour->instructor;
                            @endphp
                            {{ $instructor->user->firstname }} {{ $instructor->user->lastname }}
                        </td>
                        <td>{{ \DateTime::createFromFormat('!m', $extraHour->month)->format('F') }}</td>
                        <td>{{$extraHour->hours}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        @endif
    @endforeach
@endif

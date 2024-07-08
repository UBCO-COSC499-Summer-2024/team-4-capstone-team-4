<div class="content">
    <h1 class="content-title nos">
        <span class="content-title-text">{{ $instructor->user->firstname }} {{ $instructor->user->lastname }}'s Report</span>
    </h1>
    <div>
        <h2>Courses Performance</h2>
        <table class="w-full bg-white border border-black text-center">
            <tr>
                <th>Course Section</th>
                <th>Term</th>
                <th>Year</th>
                <th>Enrolled</th>
                <th>Dropped</th>
                <th>Capacity</th>
                <th>SEI Average</th>
            </tr>
            @php
                $courses = $instructor->teaches;
            @endphp 
            @if ($courses)
                @foreach ($courses as $course)
                    <tr>
                        <td class="border border-black">{{ $course->courseSection->name }} {{ $course->courseSection->section }}</td>
                        <td class="border border-black">Term {{ $course->courseSection->term }}</td>
                        <td class="border border-black">{{ $course->courseSection->year }}</td>
                        <td class="border border-black">{{ $course->courseSection->enrolled }}</td>
                        <td class="border border-black">{{ $course->courseSection->dropped }}</td>
                        <td class="border border-black">{{ $course->courseSection->capacity }}</td>
                        <td class="border border-black">sei</td>
                    </tr>
                @endforeach
            @endif    
        </table>
        <h2>Service Roles & Extra Hours Performance</h2>
        <table class="w-full bg-white border border-black text-center">
            <tr>
                <th class="border border-black" colspan="15">Service Roles</th>
            </tr>
            <tr>
                <th>Service Role</th>
                <th>Year</th>
                <th>Jan Hours</th>
                <th>Feb Hours</th>
                <th>Mar Hours</th>
                <th>Apr Hours</th>
                <th>May Hours</th>
                <th>Jun Hours</th>
                <th>Jul Hours</th>
                <th>Aug Hours</th>
                <th>Sep Hours</th>
                <th>Oct Hours</th>
                <th>Nov Hours</th>
                <th>Dec Hours</th>
                <th>Total Hours</th>
            </tr>
            @php
                $svcroles = $instructor->serviceRoles;
            @endphp
            @if ($svcroles)
                @foreach ($svcroles as $role)
                    @php
                        $hours = $role->monthly_hours;
                    @endphp
                    <tr>
                        <td class="border border-black">{{ $role->name }}</td>
                        <td class="border border-black">{{ $role->year }}</td>
                        <td class="border border-black">{{ $monthHours = $hours['January'] }}</td>
                        <td class="border border-black">{{ $monthHours = $hours['February'] }}</td>
                        <td class="border border-black">{{ $monthHours = $hours['March'] }}</td>
                        <td class="border border-black">{{ $monthHours = $hours['April'] }}</td>
                        <td class="border border-black">{{ $monthHours = $hours['May'] }}</td>
                        <td class="border border-black">{{ $monthHours = $hours['June'] }}</td>
                        <td class="border border-black">{{ $monthHours = $hours['July'] }}</td>
                        <td class="border border-black">{{ $monthHours = $hours['August'] }}</td>
                        <td class="border border-black">{{ $monthHours = $hours['September'] }}</td>
                        <td class="border border-black">{{ $monthHours = $hours['October'] }}</td>
                        <td class="border border-black">{{ $monthHours = $hours['November'] }}</td>
                        <td class="border border-black">{{ $monthHours = $hours['December'] }}</td>
                        <td class="border border-black">{{ array_sum($hours) }}</td>
                    </tr>
                @endforeach
            @endif 
            <tr>
                <td class="border border-black" colspan="2">Total</td>
                <td class="border border-black"></td>
                <td class="border border-black"></td>
                <td class="border border-black"></td>
                <td class="border border-black"></td>
                <td class="border border-black"></td>
                <td class="border border-black"></td>
                <td class="border border-black"></td>
                <td class="border border-black"></td>
                <td class="border border-black"></td>
                <td class="border border-black"></td>
                <td class="border border-black"></td>
                <td class="border border-black"></td>
                <td class="border border-black"></td>
            </tr>
            <tr>
                <th class="border border-black" colspan="15">Extra Hours</th>
            </tr>
            <tr>
                <th>Name</th>
                <th>Year</th>
                <th>Jan Hours</th>
                <th>Feb Hours</th>
                <th>Mar Hours</th>
                <th>Apr Hours</th>
                <th>May Hours</th>
                <th>Jun Hours</th>
                <th>Jul Hours</th>
                <th>Aug Hours</th>
                <th>Sep Hours</th>
                <th>Oct Hours</th>
                <th>Nov Hours</th>
                <th>Dec Hours</th>
                <th>Total Hours</th>
            </tr>
            @php
                $extraHours = $instructor->extraHours;
            @endphp
            @if ($extraHours)
                @foreach ($extraHours as $hours)
                    <tr>
                        <td class="border border-black">{{ $hours->name }}</td>
                        <td class="border border-black">{{ $hours->year }}</td>
                        <td class="border border-black">{{ $hours->month == '1' ? $hours->hours : '-'}}</td>
                        <td class="border border-black">{{ $hours->month == '2' ? $hours->hours : '-'}}</td>
                        <td class="border border-black">{{ $hours->month == '3' ? $hours->hours : '-'}}</td>
                        <td class="border border-black">{{ $hours->month == '4' ? $hours->hours : '-'}}</td>
                        <td class="border border-black">{{ $hours->month == '5' ? $hours->hours : '-'}}</td>
                        <td class="border border-black">{{ $hours->month == '6' ? $hours->hours : '-'}}</td>
                        <td class="border border-black">{{ $hours->month == '7' ? $hours->hours : '-'}}</td>
                        <td class="border border-black">{{ $hours->month == '8' ? $hours->hours : '-'}}</td>
                        <td class="border border-black">{{ $hours->month == '9' ? $hours->hours : '-'}}</td>
                        <td class="border border-black">{{ $hours->month == '10'? $hours->hours : '-'}}</td>
                        <td class="border border-black">{{ $hours->month == '11'? $hours->hours : '-'}}</td>
                        <td class="border border-black">{{ $hours->month == '12'? $hours->hours : '-'}}</td>
                        <td class="border border-black">{{ $hours->hours }}</td>
                    </tr>
                @endforeach
            @endif 
            <tr>
                <td colspan="15"></td>
            </tr>
            <tr>
                @php
                    $performance = $instructor->instructorPerformances()->where('year', date('Y'))->first();
                @endphp
                @if($performance)
                    <td class="border border-black" colspan="2">Target Hours</td>
                    <td class="border border-black" colspan="13">{{  $performance->target_hours }}</td>
                @endif
            </tr>
        </table>
    </div>
</div>


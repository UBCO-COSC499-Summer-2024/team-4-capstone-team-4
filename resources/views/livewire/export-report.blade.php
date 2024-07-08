<div class="content">
    <h1 class="content-title nos">
        <span class="content-title-text">{{ $instructor->user->firstname }}'s Report</span>
    </h1>
    <div>
        <h2>Courses Performance</h2>
        <table class="w-full bg-white border border-black-200">
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
            @foreach ($courses as $course)
                <tr>
                    <td>{{ $course->courseSection->name }} {{ $course->courseSection->section }}</td>
                    <td>Term {{ $course->courseSection->term }}</td>
                    <td>{{ $course->courseSection->year }}</td>
                    <td>{{ $course->courseSection->enrolled }}</td>
                    <td>{{ $course->courseSection->dropped }}</td>
                    <td>{{ $course->courseSection->capacity }}</td>
                    <td>sei</td>
                </tr>
            @endforeach
        </table>
        <h2>Service Roles Performance</h2>
        <table class="w-full bg-white border border-black-200">
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
            @foreach ($svcroles as $role)
                @php
                     $hours = json_decode($role->monthly_hours, true);
                @endphp
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->year }}</td>
                    <td>{{ $monthHours = $hours['jan'] }}</td>
                    <td>{{ $monthHours = $hours['feb'] }}</td>
                    <td>{{ $monthHours = $hours['mar'] }}</td>
                    <td>{{ $monthHours = $hours['apr'] }}</td>
                    <td>{{ $monthHours = $hours['may'] }}</td>
                    <td>{{ $monthHours = $hours['jun'] }}</td>
                    <td>{{ $monthHours = $hours['jul'] }}</td>
                    <td>{{ $monthHours = $hours['aug'] }}</td>
                    <td>{{ $monthHours = $hours['sep'] }}</td>
                    <td>{{ $monthHours = $hours['oct'] }}</td>
                    <td>{{ $monthHours = $hours['nov'] }}</td>
                    <td>{{ $monthHours = $hours['dec'] }}</td>
                    <td>{{ array_sum($hours) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2">Total</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>


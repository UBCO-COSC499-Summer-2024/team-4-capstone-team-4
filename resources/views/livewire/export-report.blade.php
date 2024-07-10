<div class="content">
    <h1 class="content-title nos">
        <span class="content-title-text">{{ $instructor->user->firstname }} {{ $instructor->user->lastname }}'s Report</span>
    </h1>
    <div>
        <h2>Courses Performance</h2>
        <table class="w-full bg-white border border-white text-center">
            <tr class="text-white bg-blue-500">
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
                $totalEnrolled = 0;
                $totalDropped = 0;
                $totalCapacity = 0;
                $seiSum = 0;
                $count = 0;
            @endphp 
            @if ($courses)
                @foreach ($courses as $course)
                    @php
                        $totalEnrolled += $course->courseSection->enrolled;
                        $totalDropped += $course->courseSection->dropped;
                        $totalCapacity += $course->courseSection->capacity;
                        $sei = \App\Models\SeiData::calculateSEIAverage($course->courseSection->id);
                        if($sei){
                            $seiSum += $sei;
                            $count++;
                        }
                    @endphp
                    <tr>
                        <td>{{ $course->courseSection->name }} {{ $course->courseSection->section }}</td>
                        <td>Term {{ $course->courseSection->term }}</td>
                        <td>{{ $course->courseSection->year }}</td>
                        <td>{{ $course->courseSection->enrolled }}</td>
                        <td>{{ $course->courseSection->dropped }}</td>
                        <td>{{ $course->courseSection->capacity }}</td>
                        <td>{{ $sei ? $sei : '-' }}</td>
                    </tr>
                @endforeach
                <tr class="font bold bg-orange-300">
                    <td colspan="3">Total</td>
                    <td>{{ $totalEnrolled }}</td>
                    <td>{{ $totalDropped }}</td>
                    <td>{{ $totalCapacity }}</td>
                    <td>{{ $count > 0 ? $seiSum/$count : '-' }}</td>
                </tr>
            @endif    
        </table>
        <br>
        <h2>Service Roles & Extra Hours Performance</h2>
        <table class="w-full bg-white border border-white text-center">
            @php
                $svcroles = $instructor->serviceRoles;
                $subtotalHours = [
                    'January' => 0, 'February' => 0, 'March' => 0, 'April' => 0,
                    'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0,
                    'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0,
                ];
                $rowcount = 0;
            @endphp
            @if ($svcroles)
                <tr class="text-white bg-blue-500">
                    <th></th>
                    <th>Name</th>
                    <th>Year</th>
                    @foreach ($subtotalHours as $month => $hours)
                        <th>{{ $month }} Hours</th>
                    @endforeach
                    <th>Total Hours</th>
                </tr>
                @foreach ($svcroles as $role)
                    @php
                        $hours = $role->monthly_hours;
                        foreach ($subtotalHours as $month => $value) {
                            $subtotalHours[$month] += $hours[$month];
                        }
                        $rowcount++;
                    @endphp
                    <tr>
                        @if($rowcount == 1)
                            <th class="border border-white bg-blue-400" rowspan="{{ count($svcroles) + 1 }}">Service Roles</th>
                        @endif
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->year }}</td>
                        @foreach ($subtotalHours as $month => $value)
                            <td>{{ $hours[$month] }}</td>
                        @endforeach
                        <td>{{ array_sum($hours) }}</td>
                    </tr>
                @endforeach
                <tr class="font-bold bg-orange-200">
                    <td colspan="2">Subtotal</td>
                    @foreach ($subtotalHours as $month => $value)
                        <td>{{ $value }}</td>
                    @endforeach
                    <td>{{ array_sum($subtotalHours) }}</td>
                </tr> 
            @endif 
            @php
                $extraHours = $instructor->extraHours;
                $extraHoursSubtotal = [
                    'January' => 0, 'February' => 0, 'March' => 0, 'April' => 0,
                    'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0,
                    'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0,
                ];
            @endphp
            @if ($extraHours)
                <tr>
                    <th class="border border-white bg-blue-400" rowspan="{{ count($extraHours) + 2 }}">Extra Hours</th>
                </tr>
            
                @foreach ($extraHours as $hours)
                    @php
                        $extraHoursSubtotal[\DateTime::createFromFormat('!m', $hours->month)->format('F')] += $hours->hours;
                    @endphp
                    <tr>
                        <td>{{ $hours->name }}</td>
                        <td>{{ $hours->year }}</td>
                        @foreach ($extraHoursSubtotal as $month => $value)
                            <td>{{ \DateTime::createFromFormat('!m', $hours->month)->format('F') == $month ? $hours->hours : '-' }}</td>
                        @endforeach
                        <td>{{ $hours->hours }}</td>
                    </tr>
                @endforeach
                <tr class="font-bold bg-orange-200">
                    <td colspan="2">Subtotal</td>
                    @foreach ($extraHoursSubtotal as $month => $value)
                        <td>{{ $value }}</td>
                    @endforeach
                    <td>{{ array_sum($extraHoursSubtotal) }}</td>
                </tr> 
            @endif
            <tr class="border-b border-white font-bold bg-orange-300">
                <td colspan="3">Total</td>
                @foreach ($subtotalHours as $month => $value)
                    <td>{{ $value + $extraHoursSubtotal[$month] }}</td>
                @endforeach
                <td>{{ array_sum($subtotalHours) + array_sum($extraHoursSubtotal) }}</td>
            </tr> 
            <tr class="font-bold bg-orange-300">
                @php
                    $performance = $instructor->instructorPerformances()->where('year', date('Y'))->first();
                @endphp
                @if($performance)
                    <td colspan="3">Target Hours</td>
                    <td colspan="12">{{  $performance->target_hours/12 }}</td>
                    <td>{{  $performance->target_hours }}</td>
                @endif
            </tr>
        </table>
    </div>
</div>

<div class="content">
    <h1 class="content-title nos">
        <span class="content-title-text">{{ $instructor->user->firstname }} {{ $instructor->user->lastname }}'s Report</span>
    </h1>
    <div>
        <h2>Courses Performance</h2>
        <table class="w-full bg-white border border-black text-center">
            <tr>
                <th class="border border-black">Course Section</th>
                <th class="border border-black">Term</th>
                <th class="border border-black">Year</th>
                <th class="border border-black">Enrolled</th>
                <th class="border border-black">Dropped</th>
                <th class="border border-black">Capacity</th>
                <th class="border border-black">SEI Average</th>
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
                        <td class="border border-black">{{ $course->courseSection->name }} {{ $course->courseSection->section }}</td>
                        <td class="border border-black">Term {{ $course->courseSection->term }}</td>
                        <td class="border border-black">{{ $course->courseSection->year }}</td>
                        <td class="border border-black">{{ $course->courseSection->enrolled }}</td>
                        <td class="border border-black">{{ $course->courseSection->dropped }}</td>
                        <td class="border border-black">{{ $course->courseSection->capacity }}</td>
                        <td class="border border-black">{{ $sei ? $sei : '-' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="border border-black" colspan="3">Total</td>
                    <td class="border border-black">{{ $totalEnrolled }}</td>
                    <td class="border border-black">{{ $totalDropped }}</td>
                    <td class="border border-black">{{ $totalCapacity }}</td>
                    <td class="border border-black">{{ $count > 0 ? $seiSum/$count : '-' }}</td>
                </tr>
            @endif    
        </table>
        <br>
        <h2>Service Roles & Extra Hours Performance</h2>
        <table class="w-full bg-white border border-black text-center">
            @php
                $svcroles = $instructor->serviceRoles;
                $subtotalHours = [
                    'January' => 0, 'February' => 0, 'March' => 0, 'April' => 0,
                    'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0,
                    'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0,
                ];
            @endphp
            @if ($svcroles)
                <tr>
                    <th class="border border-black" rowspan="{{ count($svcroles) + 2 }}">Service Roles</th>
                    <th class="border border-black">Name</th>
                    <th class="border border-black">Year</th>
                    @foreach ($subtotalHours as $month => $hours)
                        <th class="border border-black">{{ $month }} Hours</th>
                    @endforeach
                    <th class="border border-black">Total Hours</th>
                </tr>
                @foreach ($svcroles as $role)
                    @php
                        $hours = $role->monthly_hours;
                        foreach ($subtotalHours as $month => $value) {
                            $subtotalHours[$month] += $hours[$month];
                        }
                    @endphp
                    <tr>
                        <td class="border border-black">{{ $role->name }}</td>
                        <td class="border border-black">{{ $role->year }}</td>
                        @foreach ($subtotalHours as $month => $value)
                            <td class="border border-black">{{ $hours[$month] }}</td>
                        @endforeach
                        <td class="border border-black">{{ array_sum($hours) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="border border-black" colspan="2">Subtotal</td>
                    @foreach ($subtotalHours as $month => $value)
                        <td class="border border-black">{{ $value }}</td>
                    @endforeach
                    <td class="border border-black">{{ array_sum($subtotalHours) }}</td>
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
                    <th class="border border-black" rowspan="{{ count($extraHours) + 2 }}">Extra Hours</th>
                </tr>
            
                @foreach ($extraHours as $hours)
                    @php
                        $extraHoursSubtotal[\DateTime::createFromFormat('!m', $hours->month)->format('F')] += $hours->hours;
                    @endphp
                    <tr>
                        <td class="border border-black">{{ $hours->name }}</td>
                        <td class="border border-black">{{ $hours->year }}</td>
                        @foreach ($extraHoursSubtotal as $month => $value)
                            <td class="border border-black">{{ \DateTime::createFromFormat('!m', $hours->month)->format('F') == $month ? $hours->hours : '-' }}</td>
                        @endforeach
                        <td class="border border-black">{{ $hours->hours }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="border border-black" colspan="2">Subtotal</td>
                    @foreach ($extraHoursSubtotal as $month => $value)
                        <td class="border border-black">{{ $value }}</td>
                    @endforeach
                    <td class="border border-black">{{ array_sum($extraHoursSubtotal) }}</td>
                </tr> 
            @endif
            <tr>
                <td class="border border-black" colspan="3">Total</td>
                @foreach ($subtotalHours as $month => $value)
                    <td class="border border-black">{{ $value + $extraHoursSubtotal[$month] }}</td>
                @endforeach
                <td class="border border-black">{{ array_sum($subtotalHours) + array_sum($extraHoursSubtotal) }}</td>
            </tr> 
            <tr>
                @php
                    $performance = $instructor->instructorPerformances()->where('year', date('Y'))->first();
                @endphp
                @if($performance)
                    <td class="border border-black" colspan="3">Target Hours</td>
                    <td class="border border-black" colspan="13">{{  $performance->target_hours }}</td>
                @endif
            </tr>
        </table>
    </div>
</div>

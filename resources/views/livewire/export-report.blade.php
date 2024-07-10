<div class="content">
    <h1 class="content-title nos">
        <span class="content-title-text">{{ $instructor->user->firstname }} {{ $instructor->user->lastname }}'s Report</span>
    </h1>
    <div>
        <h2 class="font-bold ">Courses Performance</h2>
        @php
            $courses = $instructor->teaches;
            $totalEnrolled = 0;
            $totalDropped = 0;
            $totalCapacity = 0;
            $seiSum = 0;
            $count = 0;
        @endphp 
        @if ($courses !== null)
            <table class="w-full bg-white border border-gray-300 text-center ">
                <tr class="text-white bg-slate-500">
                    <th>Course Section</th>
                    <th>Term</th>
                    <th>Year</th>
                    <th>Enrolled</th>
                    <th>Dropped</th>
                    <th>Capacity</th>
                    <th>SEI Average</th>
                </tr>
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
                            <td class="border border-gray-300">{{ $course->courseSection->name }} {{ $course->courseSection->section }}</td>
                            <td class="border border-gray-300">Term {{ $course->courseSection->term }}</td>
                            <td class="border border-gray-300">{{ $course->courseSection->year }}</td>
                            <td class="border border-gray-300">{{ $course->courseSection->enrolled }}</td>
                            <td class="border border-gray-300">{{ $course->courseSection->dropped }}</td>
                            <td class="border border-gray-300">{{ $course->courseSection->capacity }}</td>
                            <td class="border border-gray-300">{{ $sei ? $sei : '-' }}</td>
                        </tr>
                    @endforeach
                    <tr class="font bold bg-gray-400">
                        <td colspan="3">Total</td>
                        <td class="border border-gray-300">{{ $totalEnrolled }}</td>
                        <td class="border border-gray-300">{{ $totalDropped }}</td>
                        <td class="border border-gray-300">{{ $totalCapacity }}</td>
                        <td class="border border-gray-300">{{ $count > 0 ? $seiSum/$count : '-' }}</td>
                    </tr>  
            </table>
        @else
            <p>No courses found for this instructor.</p>
        @endif  
        <br>
        <h2>Service Roles & Extra Hours Performance</h2>
        @php
            $svcroles = $instructor->serviceRoles;
            $subtotalHours = [
                'January' => 0, 'February' => 0, 'March' => 0, 'April' => 0,
                'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0,
                'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0,
            ];
            $rowcount = 0;

            $extraHours = $instructor->extraHours;
                $extraHoursSubtotal = [
                    'January' => 0, 'February' => 0, 'March' => 0, 'April' => 0,
                    'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0,
                    'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0,
                ];
        @endphp
         @if ($svcroles !== null || $extraHours !== null)
        <table class="w-full bg-white border border-gray-300 text-center">
                <tr class="text-white bg-slate-500">
                    <th></th>
                    <th>Name</th>
                    <th>Year</th>
                    @foreach ($subtotalHours as $month => $hours)
                        <th>{{ substr($month, 0, 3) }} Hours</th>
                    @endforeach
                    <th>Total Hours</th>
                </tr>
               @if($svcroles !== null) 
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
                            <th class="border border-white bg-slate-500" rowspan="{{ count($svcroles) + 1 }}">Service Roles</th>
                        @endif
                        <td class="border border-gray-300">{{ $role->name }}</td>
                        <td class="border border-gray-300">{{ $role->year }}</td>
                        @foreach ($subtotalHours as $month => $value)
                            <td class="border border-gray-300">{{ $hours[$month] }}</td>
                        @endforeach
                        <td class="border border-gray-300">{{ array_sum($hours) }}</td>
                    </tr>
                @endforeach
                <tr class="font-bold bg-gray-300">
                    <td colspan="2">Subtotal</td>
                    @foreach ($subtotalHours as $month => $value)
                        <td class="border border-gray-300">{{ $value }}</td>
                    @endforeach
                    <td class="border border-gray-300">{{ array_sum($subtotalHours) }}</td>
                </tr> 
            @else
                <p>No service roles found for this instructor.</p>
            @endif 
            @if ($extraHours)
                <tr>
                    <th class="border border-white bg-slate-500" rowspan="{{ count($extraHours) + 2 }}">Extra Hours</th>
                </tr>
            
                @foreach ($extraHours as $hours)
                    @php
                        $extraHoursSubtotal[\DateTime::createFromFormat('!m', $hours->month)->format('F')] += $hours->hours;
                    @endphp
                    <tr>
                        <td class="border border-gray-300">{{ $hours->name }}</td>
                        <td class="border border-gray-300">{{ $hours->year }}</td>
                        @foreach ($extraHoursSubtotal as $month => $value)
                            <td class="border border-gray-300">{{ \DateTime::createFromFormat('!m', $hours->month)->format('F') == $month ? $hours->hours : '-' }}</td>
                        @endforeach
                        <td class="border border-gray-300">{{ $hours->hours }}</td>
                    </tr>
                @endforeach
                <tr class="font-bold bg-gray-300">
                    <td colspan="2">Subtotal</td>
                    @foreach ($extraHoursSubtotal as $month => $value)
                        <td class="border border-gray-300">{{ $value }}</td>
                    @endforeach
                    <td class="border border-gray-300">{{ array_sum($extraHoursSubtotal) }}</td>
                </tr>
            @else
                <p>No extra hours found for this instructor.</p>
            @endif
            <tr class="border-b border-white font-bold bg-gray-400">
                <td colspan="3">Total</td>
                @foreach ($subtotalHours as $month => $value)
                    <td class="border border-gray-300">{{ $value + $extraHoursSubtotal[$month] }}</td>
                @endforeach
                <td class="border border-gray-300">{{ array_sum($subtotalHours) + array_sum($extraHoursSubtotal) }}</td>
            </tr> 
            <tr class="font-bold bg-gray-400">
                @php
                    $performance = $instructor->instructorPerformances()->where('year', date('Y'))->first();
                @endphp
                @if($performance)
                    <td class="border border-gray-300" colspan="3">Target Hours</td>
                    <td class="border border-gray-300" colspan="12">{{  $performance->target_hours/12 }}</td>
                    <td class="border border-gray-300">{{  $performance->target_hours }}</td>
                @endif
            </tr>
        </table>
        @else
            <p>No performance data found for this instructor.</p>
        @endif
    </div>
</div>



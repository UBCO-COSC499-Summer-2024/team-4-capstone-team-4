<table>
    <thead>
        <tr>
            <th style="font-weight:bold; font-size:18px;">
                {{ $instructor->user->firstname }} {{ $instructor->user->lastname }}'s Report
            </th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

@if ($courses->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th style="font-weight:bold;">Course Section</th>
                <th style="font-weight:bold;">Term</th>
                <th style="font-weight:bold;">Year</th>
                <th style="font-weight:bold;">Enrolled (%)</th>
                <th style="font-weight:bold;">Dropped (%)</th>
                <th style="font-weight:bold;">SEI Average (IM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($courses as $course)
                @php
                    $sei = \App\Models\SeiData::calculateSEIAverage($course->courseSection->id);
                    $capacity = $course->courseSection->capacity;
                @endphp
                <tr>
                    <td>{{ $course->courseSection->prefix }}{{ $course->courseSection->number }} {{ $course->courseSection->section }}</td>
                    <td>Term {{ $course->courseSection->term }}</td>
                    <td>{{ $course->courseSection->year }}</td>
                    <td>{{ round($course->courseSection->enrolled * 100 / $capacity, 1) }}</td>
                    <td>{{ round($course->courseSection->dropped * 100 / $capacity, 1) }}</td>
                    <td>{{ $sei ?? '-' }}</td>
                </tr>
            @endforeach
            @if ($performance)
                <tr>
                    <td colspan="3"><strong>Total Average</strong></td>
                    <td><strong>{{ $performance->enrolled_avg }}</strong></td>
                    <td><strong>{{ $performance->dropped_avg }}</strong></td>
                    <td><strong>{{ $performance->sei_avg }}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>
@else
    <p>No courses found for this instructor.</p>
@endif  

@php
    $subtotalHours = [
        'January' => 0, 'February' => 0, 'March' => 0, 'April' => 0,
        'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0,
        'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0,
    ];
    $rowcount = 0;

    $extraHoursSubtotal = [
        'January' => 0, 'February' => 0, 'March' => 0, 'April' => 0,
        'May' => 0, 'June' => 0, 'July' => 0, 'August' => 0,
        'September' => 0, 'October' => 0, 'November' => 0, 'December' => 0,
    ];
@endphp

@if ($svcroles->isNotEmpty() || $extraHours->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th style="font-weight:bold;"></th>
                <th style="font-weight:bold;">Name</th>
                <th style="font-weight:bold;">Year</th>
                @foreach ($subtotalHours as $month => $hours)
                    <th style="font-weight:bold;">{{ substr($month, 0, 3) }} Hours</th>
                @endforeach
                <th style="font-weight:bold;">Total Hours</th>
            </tr>
        </thead>
        <tbody>
            @if ($svcroles->isNotEmpty())
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
                            <th rowspan="{{ count($svcroles) + 1 }}">
                                <strong>Service Roles</strong>
                            </th>
                        @endif
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->year }}</td>
                        @foreach ($subtotalHours as $month => $value)
                            <td>{{ $hours[$month] }}</td>
                        @endforeach
                        <td>{{ array_sum($hours) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2"><strong>Subtotal</strong></td>
                    @foreach ($subtotalHours as $month => $value)
                        <td>{{ $value }}</td>
                    @endforeach
                    <td>{{ array_sum($subtotalHours) }}</td>
                </tr> 
            @endif 

            @php $rowcount = 0; @endphp
            
            @if ($extraHours->isNotEmpty())
                @foreach ($extraHours as $hours)
                    @php
                        $rowcount++;
                        $extraHoursSubtotal[\DateTime::createFromFormat('!m', $hours->month)->format('F')] += $hours->hours;
                    @endphp
                    <tr>
                        @if($rowcount == 1)
                            <th rowspan="{{ $extraHours->count() + 1 }}">
                                <strong>Extra Hours</strong>
                            </th>
                        @endif
                        <td>{{ $hours->name }}</td>
                        <td>{{ $hours->year }}</td>
                        @foreach ($extraHoursSubtotal as $month => $value)
                            <td>{{ \DateTime::createFromFormat('!m', $hours->month)->format('F') == $month ? $hours->hours : '-' }}</td>
                        @endforeach
                        <td>{{ $hours->hours }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2"><strong>Subtotal</strong></td>
                    @foreach ($extraHoursSubtotal as $month => $value)
                        <td><strong>{{ $value }}</strong></td>
                    @endforeach
                    <td><strong>{{ array_sum($extraHoursSubtotal) }}</strong></td>
                </tr>
            @endif

            <tr>
                <td colspan="3"><strong>Total</strong></td>
                @foreach ($subtotalHours as $month => $value)
                    <td><strong>{{ $value + $extraHoursSubtotal[$month] }}</strong></td>
                @endforeach
                <td><strong>{{ array_sum($subtotalHours) + array_sum($extraHoursSubtotal) }}</strong></td>
            </tr> 
            <tr>
                @if ($performance)
                    <td colspan="3"><strong>Target Hours</strong></td>
                    <td colspan="12"><strong>{{ round($performance->target_hours / 12) }}</strong></td>
                    <td><strong>{{ $performance->target_hours }}</strong></td>
                @endif
            </tr>
        </tbody>
    </table>
@else
    <p>No performance data found for this instructor.</p>
@endif

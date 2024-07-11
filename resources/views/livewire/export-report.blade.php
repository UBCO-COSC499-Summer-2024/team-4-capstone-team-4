<div class="content">
    <h1 class="content-title nos">
        <span class="content-title-text">{{ $instructor->user->firstname }} {{ $instructor->user->lastname }}'s Report</span>
    </h1>
    <div>
        <div>
            <label for="year">Select Year:</label>
            <select wire:model.live="year" id="year" name="year">
                @php
                    $allPerformances = $instructor->instructorPerformances;
                @endphp
                @foreach ($allPerformances as $perf)
                    <option value="{{ $perf->year }}" {{ $perf->year == $year ? 'selected' : '' }}>{{ $perf->year }}</option>
                @endforeach
            </select>
        </div>
        <h2 class="font-bold">Courses Performance</h2>
        @if ($courses->isNotEmpty())
            <table class="w-full bg-white border border-gray-300 text-center">
                <tr class="text-white bg-slate-500">
                    <th>Course Section</th>
                    <th>Term</th>
                    <th>Year</th>
                    <th>Enrolled (%)</th>
                    <th>Dropped (%)</th>
                    <th>SEI Average (IM)</th>
                </tr>
                @foreach ($courses as $course)
                    @php
                        $sei = \App\Models\SeiData::calculateSEIAverage($course->courseSection->id);
                        $capacity = $course->courseSection->capacity;
                    @endphp
                    <tr>
                        <td class="border border-gray-300">{{ $course->courseSection->prefix }}{{ $course->courseSection->number }}  {{ $course->courseSection->section }}</td>
                        <td class="border border-gray-300">Term {{ $course->courseSection->term }}</td>
                        <td class="border border-gray-300">{{ $course->courseSection->year }}</td>
                        <td class="border border-gray-300">{{ round($course->courseSection->enrolled * 100 / $capacity, 1) }}</td>
                        <td class="border border-gray-300">{{ round($course->courseSection->dropped * 100 / $capacity, 1) }}</td>
                        <td class="border border-gray-300">{{ $sei ? $sei : '-' }}</td>
                    </tr>
                @endforeach
                @if ($performance)
                    <tr class="font-bold bg-gray-400">
                        <td colspan="3">Total Average</td>
                        <td class="border border-gray-300">{{ $performance->enrolled_avg }}</td>
                        <td class="border border-gray-300">{{ $performance->dropped_avg }}</td>
                        <td class="border border-gray-300">{{ $performance->sei_avg }}</td>
                    </tr>
                @endif
            </table>
        @else
            <p>No courses found for this instructor.</p>
        @endif  

        <br>

        <h2 class="font-bold">Service Roles & Extra Hours Performance</h2>
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
                    <tr>
                        <td colspan="{{ count($subtotalHours) + 2 }}">No service roles found for this instructor.</td>
                    </tr>
                @endif 

                @if ($extraHours->isNotEmpty())
                    <tr>
                        <th class="border border-white bg-slate-500" rowspan="{{ $extraHours->count() + 2 }}">Extra Hours</th>
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
                    <tr>
                        <td colspan="{{ count($subtotalHours) + 2 }}">No extra hours found for this instructor.</td>
                    </tr>
                @endif

                <tr class="border-b border-white font-bold bg-gray-400">
                    <td colspan="3">Total</td>
                    @foreach ($subtotalHours as $month => $value)
                        <td class="border border-gray-300">{{ $value + $extraHoursSubtotal[$month] }}</td>
                    @endforeach
                    <td class="border border-gray-300">{{ array_sum($subtotalHours) + array_sum($extraHoursSubtotal) }}</td>
                </tr> 
                <tr class="font-bold bg-gray-400">
                    @if ($performance)
                        <td class="border border-gray-300" colspan="3">Target Hours</td>
                        <td class="border border-gray-300" colspan="12">{{  round($performance->target_hours/12) }}</td>
                        <td class="border border-gray-300">{{  $performance->target_hours }}</td>
                    @endif
                </tr>
            </table>
        @else
            <p>No performance data found for this instructor.</p>
        @endif
    </div>
</div>

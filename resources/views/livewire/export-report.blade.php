<div class="content">
    <h1 class="content-title nos">
        <span class="content-title-text">{{ $instructor->user->firstname }} {{ $instructor->user->lastname }}'s Report</span>
    </h1>
    <div>
        <div class="flex justify-end items-center">
            <div class="mr-2">
                <label for="year">Select Year:</label>
                <select wire:model.live="year" id="year" name="year" class="w-auto min-w-[75px] text-gray-500 bg-white report-cell focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    @php
                        $allPerformances = $instructor->instructorPerformances->sortBy('year');
                    @endphp
                    @foreach ($allPerformances as $perf)
                        <option value="{{ $perf->year }}" {{ $perf->year == $year ? 'selected' : '' }}>{{ $perf->year }}</option>
                    @endforeach
                </select>
            </div>
            <x-report-dropdown/>
        </div>
        <div id="exportContent" class="ml-2">
            <h2 class="font-bold">Courses Performance</h2>
            @if ($courses->isNotEmpty())
                <table id="courseTable" class="report-table">
                    <thead>    
                        <tr class="text-white bg-[#3b4779]">
                            <th class="report-head-cell">Course Section</th>
                            <th class="report-head-cell">Term</th>
                            <th class="report-head-cell">Year</th>
                            <th class="report-head-cell">Enrolled (%)</th>
                            <th class="report-head-cell">Dropped (%)</th>
                            <th class="report-head-cell">SEI Average (IM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courses as $course)
                            @php
                                $sei = \App\Models\SeiData::calculateSEIAverage($course->courseSection->id);
                                $capacity = $course->courseSection->capacity;
                            @endphp
                            <tr class="report-row">
                                <td class="report-cell">{{ $course->courseSection->prefix }}{{ $course->courseSection->number }}  {{ $course->courseSection->section }}</td>
                                <td class="report-cell">Term {{ $course->courseSection->term }}</td>
                                <td class="report-cell">{{ $course->courseSection->year }}</td>
                                <td class="report-cell">{{ round($course->courseSection->enroll_end * 100 / $capacity, 1) }}</td>
                                <td class="report-cell">{{ round($course->courseSection->dropped * 100 / $capacity, 1) }}</td>
                                <td class="report-cell">{{ $sei ? $sei : '-' }}</td>
                            </tr>
                        @endforeach
                        @if ($performance)
                            <tr class="report-row total-row">
                                <td class="report-cell" colspan="3">Total Average</td>
                                <td class="report-cell">{{ $performance->enrolled_avg }}</td>
                                <td class="report-cell">{{ $performance->dropped_avg }}</td>
                                <td class="report-cell">{{ $performance->sei_avg }}</td>
                            </tr>
                        @endif
                    </tbody>
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
                <table id="performanceTable" class="report-table">
                    <thead>
                        <tr class="text-white bg-[#3b4779]">
                            <th class="report-head-cell"></th>
                            <th class="report-head-cell">Name</th>
                            <th class="report-head-cell">Year</th>
                            @foreach ($subtotalHours as $month => $hours)
                                <th class="report-head-cell">{{ substr($month, 0, 3) }}</th>
                            @endforeach
                            <th class="report-head-cell">Total Hrs</th>
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
                                <tr class="report-row">
                                    @if($rowcount == 1)
                                        <th class="border border-white border-l-gray-500 bg-[#3b4779] text-white px-2" rowspan="{{ count($svcroles) + 1 }}">Service Roles</th>
                                    @endif
                                    <td class="report-cell">{{ $role->name }}</td>
                                    <td class="report-cell">{{ $role->year }}</td>
                                    @foreach ($subtotalHours as $month => $value)
                                        <td class="report-cell">{{ $hours[$month] }}</td>
                                    @endforeach
                                    <td class="report-cell">{{ array_sum($hours) }}</td>
                                </tr>
                            @endforeach
                            <tr class="report-row subtotal-row">
                                <td class="report-cell" colspan="2">Subtotal</td>
                                @foreach ($subtotalHours as $month => $value)
                                    <td class="report-cell">{{ $value }}</td>
                                @endforeach
                                <td class="report-cell">{{ array_sum($subtotalHours) }}</td>
                            </tr> 
                        @endif 

                        @php $rowcount = 0; @endphp
                        
                        @if ($extraHours->isNotEmpty())
                            @foreach ($extraHours as $hours)
                                @php
                                    $rowcount++;
                                    $extraHoursSubtotal[\DateTime::createFromFormat('!m', $hours->month)->format('F')] += $hours->hours;
                                @endphp
                                <tr class="report-row">
                                    @if($rowcount == 1)
                                        <th class="border border-white border-l-gray-500 bg-[#3b4779] text-white px-2" rowspan="{{ $extraHours->count() + 1 }}">Extra Hours</th>
                                    @endif
                                    <td class="report-cell">{{ $hours->name }}</td>
                                    <td class="report-cell">{{ $hours->year }}</td>
                                    @foreach ($extraHoursSubtotal as $month => $value)
                                        <td class="report-cell">{{ \DateTime::createFromFormat('!m', $hours->month)->format('F') == $month ? $hours->hours : '-' }}</td>
                                    @endforeach
                                    <td class="report-cell">{{ $hours->hours }}</td>
                                </tr>
                            @endforeach
                            <tr class="report-row subtotal-row">
                                <td class="report-cell" colspan="2">Subtotal</td>
                                @foreach ($extraHoursSubtotal as $month => $value)
                                    <td class="report-cell">{{ $value }}</td>
                                @endforeach
                                <td class="report-cell">{{ array_sum($extraHoursSubtotal) }}</td>
                            </tr>
                        @endif

                        <tr class="report-row border-b border-white total-row">
                            <td class="report-cell" colspan="3">Total</td>
                            @foreach ($subtotalHours as $month => $value)
                                <td class="report-cell">{{ $value + $extraHoursSubtotal[$month] }}</td>
                            @endforeach
                            <td class="report-cell">{{ array_sum($subtotalHours) + array_sum($extraHoursSubtotal) }}</td>
                        </tr> 
                        <tr class="report-row total-row">
                            @if ($performance)
                                <td class="report-cell" colspan="3">Target Hours</td>
                                <td class="report-cell" colspan="12">{{  round($performance->target_hours/12) }}</td>
                                <td class="report-cell">{{  $performance->target_hours }}</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            @else
                <p>No service roles/extra hours performance data found for this instructor.</p>
            @endif
        </div>
    </div>
</div>

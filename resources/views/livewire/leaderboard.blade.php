<div class="relative sm:rounded-lg">
    <div class="sticky top-0 z-10 flex items-center justify-between h-20 bg-white dark:bg-gray-900">
        <div class="flex justify-between items-center w-full px-4">
            <h1 class="text-2xl font-bold text-gray-900">LEADERBOARD</h1>
            <div class="ml-auto flex items-center">
                <x-area-filter />
                <label for="year" style="padding-right: 5px;">Select Year:</label>
                <select wire:model.live="year" id="year" name="year" class="w-auto min-w-[75px] text-gray-500 bg-white report-cell focus:outline-none hover:text-white hover:bg-[#3b4779] focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    @php
                        $user = Auth::user();
                        $dept_id = App\Models\UserRole::find($user->id)->department_id;
                        $dept = App\Models\Department::find($dept_id);
                        $allPerformances = $dept->departmentPerformance->sortBy('year');
                        $rank = 0;
                    @endphp
                    @foreach ($allPerformances as $perf)
                        <option value="{{ $perf->year }}" {{ $perf->year == $year ? 'selected' : '' }}>{{ $perf->year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <form wire:submit.prevent="submit">
        @csrf
        <x-staff-table>
            <x-leaderboard-header />
            <tbody>
                @if(isset($users))
                    @foreach ($users as $user)
                        @php
                            $area_names = [];
                            $instructor = App\Models\UserRole::find($user->instructor_id);
                            $performance = App\Models\InstructorPerformance::where('instructor_id', $user->instructor_id)
                                                                            ->where('year', $year)
                                                                            ->first();
                            if($performance) {
                                $rank++;
                            }                                                      
                        @endphp
                        <x-leaderboard-row
                            rank="{{$rank}}"
                            rankString="{{ $this->addOrdinalSuffix($rank) }}"
                            count="{{ count($users) }}"
                            instructorId="{{ $user->instructor_id }}"
                            src="{{ $user->profile_photo_url }}"
                            fullname="{{ $user->firstname }} {{ $user->lastname }}"
                            email="{{ $user->email }}"
                            score="{{ $performance->score ?? '-' }}"
                            badge="{{ $user->profile_photo_url }}"
                        />
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No users</td>
                    </tr>
                @endif
            </tbody>
        </x-staff-table>
    </form>
</div>





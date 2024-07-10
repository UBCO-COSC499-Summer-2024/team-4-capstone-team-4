<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="sticky top-0 z-10 h-20 flex items-center justify-between flex-wrap md:flex-nowrap space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">
        <x-staff-search />
        <div class="flex items-center space-x-4">
            <x-staff-filter />
            <x-staff-button wire:click="save" id="staff-save" name="staff-save">Save</x-staff-button>
            <x-staff-button wire:click="exit" id="staff-exit" name="staff-exit">Exit</x-staff-button>
        </div>
    </div> 
    @if(session()->has('error'))
        <div class="text-sm text-red-600">
            {{ session('error') }}
        </div>
    @endif
         
    <x-staff-table>
        <x-staff-table-header :sortField="$sortField" :sortDirection="$sortDirection" />
        <tbody> 
            @if(isset($users))
                @foreach ($users as $user) 
                    @php
                        $area_names = [];
                        $instructor = App\Models\UserRole::find($user->instructor_id);
                        $performance = App\Models\InstructorPerformance::where('instructor_id', $user->instructor_id)
                                                                        ->where('year', date('Y'))
                                                                        ->first();
                        if($performance){
                            $totalHours = json_decode($performance->total_hours, true);
                            $currentMonthHours = $totalHours[date('F')];
                        }else{
                            $currentMonthHours = null;
                        }

                        if ($instructor && $instructor->teaches) {
                            $course_ids = $instructor->teaches->pluck('course_section_id')->all();
                            
                            foreach ($course_ids as $course_id) {
                                $course = App\Models\CourseSection::find($course_id);
                                $area_name = $course->area->name ?? null;
            
                                if ($area_name && !in_array($area_name, $area_names)) {
                                    $area_names[] = $area_name;
                                }
                            }
                        }
                    @endphp
            
                    <x-staff-table-row-edit 
                        fullname="{{ $user->firstname }} {{ $user->lastname }}" 
                        email="{{ $user->email }}" 
                        subarea="{{ empty($area_names) ? '-' : implode(', ', $area_names) }}" 
                        completedHours="{{ $currentMonthHours ?? '-' }}" 
                        targetHours="{{ $performance->target_hours ?? '-' }}" 
                        rating="{{ $performance->score ?? '-' }}" 
                        src="{{ $user->profile_photo_url }}" 
                    />
                @endforeach
            @endif       
        </tbody>
    </x-staff-table>
</div> 
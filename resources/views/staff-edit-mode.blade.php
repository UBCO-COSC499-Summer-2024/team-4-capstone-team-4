<x-app-layout>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="flex items-center justify-between flex-wrap md:flex-nowrap space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">
            <x-staff-search-edit />
            <div class="flex items-center space-x-4">
                <x-staff-filter-edit />
                <x-button form="editForm" id="staff-save" name="staff-save">Save</x-button>
            </div>
        </div> 
        <form id=editForm method="POST" action="{{ route('staff-edit-mode')}}">
            @csrf          
        <x-staff-table>
            <x-staff-table-header-edit :sortField="$sortField" :sortDirection="$sortDirection" :query="$query" :areas="$areas" />
            <tbody>
                @if(isset($users))
                    @if(empty($users))
                        <p>No users found.</p>
                    @else
                        @foreach ($users as $user)
                            @php
                                $area_names=[];
                                $instructor = $user->roles->where('role', 'instructor')->first();
                                $performance = App\Models\InstructorPerformance::where('instructor_id',  $instructor->id )->first();
                                $course_ids = $instructor->teaches->pluck('course_section_id')->all();
                                foreach ($course_ids as $course_id){
                                    $course = App\Models\CourseSection::find($course_id);
                                    $area_names[] = $course->area->name;
                                }
                                //$user = App\Models\User::find($instructor->user_id);
                            @endphp
                            <x-staff-table-row-edit 
                                fullname="{{ $user->firstname }} {{ $user->lastname }}" 
                                email="{{ $user->email }}" 
                                subarea="{{ empty($area_names) ? '-' : implode(', ', $area_names) }}" 
                                completedHours="{{ $performance ? ($performance->total_hours ?? '-') : '-' }}" 
                                targetHours="{{ $performance ? ($performance->target_hours ?? '-') : '-' }}" 
                                rating="{{ $performance ? ($performance->score ?? '-') : '-' }}" 
                                src="{{ $user->profile_photo_url }}" 
                            />
                            <input type="hidden" name="email[]" value="{{ $user->email }}" />
                        @endforeach
                    @endif
                @endif
            </tbody>
        </x-staff-table>
        </form>  
    </div> 
</x-app-layout>
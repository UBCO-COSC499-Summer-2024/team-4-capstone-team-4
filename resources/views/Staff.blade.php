<x-app-layout>
   {{--  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="flex items-center justify-between flex-wrap md:flex-nowrap space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">
            <x-staff-search />
            <div class="flex items-center space-x-4">
                <x-staff-filter />
                <x-staff-dropdown />
            </div>
        </div> 
        <form method="POST" action="{{ route('staff')}}">
            @csrf
        <x-staff-targethours/>           
        <x-staff-table>
            <x-staff-table-header :sortField="$sortField" :sortDirection="$sortDirection" :query="$query" :areas="$areas" />
            <tbody>
                @if(isset($users))
                    @if(empty($users) || $users == null)
                        <p>No users found.</p>
                    @else
                        @foreach ($users as $user)
                            @php
                                $area_names=[];
                                $instructor = App\Models\UserRole::find($user->instructor_id);
                                //$performance = App\Models\InstructorPerformance::where('instructor_id',  $user->instructor_id)->first();
                                $course_ids = $instructor->teaches->pluck('course_section_id')->all();
                                if($course_ids !== null){
                                    foreach ($course_ids as $course_id){
                                        $course = App\Models\CourseSection::find($course_id);
                                        $area_names[] = $course->area->name;
                                    }
                                }
                            @endphp
                            <x-staff-table-row 
                                fullname="{{ $user->firstname }} {{ $user->lastname }}" 
                                email="{{ $user->email }}" 
                                subarea="{{ empty($area_names) ? '-' : implode(', ', $area_names) }}" 
                                completedHours="{{ $user->total_hours }}" 
                                targetHours="{{$user->target_hours ?? '-' }}" 
                                rating="{{ $user->score }}"
                                src="{{ $user->profile_photo_url }}" 
                            />
                        @endforeach
                    @endif
                @endif
            </tbody>
        </x-staff-table>
        </form>  
    </div>  --}}
    @livewire('staff-list')
</x-app-layout>
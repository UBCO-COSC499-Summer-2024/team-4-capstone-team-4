@php
    $user = Auth::user();
@endphp

<div class="relative">
    <form wire:submit.prevent="handleSubmit">
        <div class="overflow-x-auto shadow-sm rounded-md">
            <div class="flex justify-between items-center">
                <div class="italic">*Note: Not all courses need to be assigned to save</div>
                <div class="flex justify-end space-x-2">
                <button type="submit" class="import-form-save-button">
                    <span class="material-symbols-outlined">save</span>
                    Save
                </button>
                </div>
            </div>

            <div class="py-3 flex justify-between bg-[#3b4779] text-white rounded-t-md">
                <div class="w-10/12 text-left mx-2">Course Section</div>
                <div class="w-8/12 text-left mx-2">Instructor</div>
                <div class="w-5/12"></div>
            </div>
            @if($finalCSVs)
                @foreach($assignments as $index => $assignment)  
                {{-- {{ dd($assignments[$index]['instructor'], $availableInstructors ) }}  --}}
                <div class="import-form-row">
                    @php
                        // $course = $availableCourses->firstWhere('id', $assignment['course_section_id']);

                        $courseId = $assignment['course_section_id'] ?? null;
                        $course = $courseId ? $availableCourses->firstWhere('id', $courseId) : null;
                        // dd($course);
                    @endphp
                    @if($course)
                    <div class="w-10/12 text-left text-sm">
                        <div>{{ $course->prefix }} {{ $course->number }} {{ $course->section }} - {{ $course->year }}{{ $course->session }} Term {{ $course->term }}</div>
                    </div>
                    <div class="w-8/12 text-center">
                        <select wire:model="assignments.{{ $index }}.instructor_id" class="import-form-select">
                            <option value="">Select Instructor</option>
                            @foreach($availableInstructors as $instructor)
                                @if($assignments[$index]['instructor'] == "{$instructor->firstname} {$instructor->lastname}")
                                    <option value="{{ $instructor->id }}">
                                        {{ $instructor->firstname }} {{ $instructor->lastname }}
                                    </option>  
                                @else
                                    <option value="{{ $instructor->id }}">
                                        {{ $instructor->firstname }} {{ $instructor->lastname }}
                                    </option> 
                                @endif
                            @endforeach
                        </select>  
                        @foreach($availableInstructors as $instructor)
                            @if($assignments[$index]['instructor'] == "{$instructor->firstname} {$instructor->lastname}")
                                <div class="text-green-500">Instructor Found!</div>
                            @endif
                        @endforeach 
                        
                            {{-- <x-custom-search-select :availableInstructors="$availableInstructors" 
                            :selectedInstructorId="$assignments[$index]['instructor_id']"
                            :selectedInstructorName="$assignments[$index]['instructor']"/>
                            <p> Selected instructor: {{ $selectedInstructorId }}</p> --}}

                    </div>
                    <div class="w-5/12"></div>
                    @endif
                </div>
            @endforeach
            @endif
        </div>
    </form>

    <div wire:loading wire:target="handleSubmit" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="text-white text-xl text-center m-80">Saving...</div>
    </div>

    {{-- @if(!$hasCourses) 
        <div class="flex flex-col items-center justify-center mt-10 text-gray-500">
            <div class="text-center text-4xl">Create a course section to assign it!</div>
            <button class="bg-white text-[#3B784F] border border-[#3B784F] py-2 px-4 mx-2 my-5 rounded-lg hover:bg-[#3B784F] hover:text-white" 
            onclick="location.href='{{ route('import') }}'">
                Create Course Section
            </button>
        </div>
    @endif --}}

    @if(session('success'))
        @if($showModal) 
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                @include('components.import-modal', ['user' => $user, 'moreText' => 'Assign More'])
            </div>
        @endif
    @endif

</div>



@php
    $user = Auth::user();
@endphp

<div class="relative">
    <button class="import-form-add-button" onclick="location.href='{{ route('upload.file.show.assign.courses') }}'">
        <span class="material-symbols-outlined">upload</span>
        Upload CSV File to Assign
    </button>
    <form wire:submit.prevent="handleSubmit">
        <div class="overflow-x-auto shadow-sm rounded-md">
            @if($hasCourses)
            <div class="absolute top-0 right-0 space-x-2">
            <button type="submit" class="import-form-save-button">
                <span class="material-symbols-outlined">save</span>
                Save
            </button>
            </div>
            @endif
            <div class="py-3 flex justify-between bg-[#3b4779] text-white rounded-t-md">
                <div class="w-3/12"></div>
                <div class="w-10/12 text-center mx-2">Course Section</div>
                <div class="w-3/12 text-center mx-2">Instructor</div>
                <div class="w-6/12"></div>
                <div class="w-3/12"></div>
            </div>
            @if($hasCourses)
                @foreach($assignments as $index => $assignment)
                    <div class="import-form-row">
                        @php
                            $course = $availableCourses->firstWhere('id', $assignment['course_section_id']);
                        @endphp
                         <div class="w-3/12"></div>
                        <div class="w-10/12 text-center">
                            <div>{{ $course->prefix }} {{ $course->number }} {{ $course->section }} - {{ $course->year }}{{ $course->session }} Term {{ $course->term }}</div>
                        </div>
                        <div class="w-3/12 text-center">
                            {{-- basic select to fall back on --}}

                            {{-- <select wire:model="assignments.{{ $index }}.instructor_id" class="import-form-select">
                                <option value="">Select Instructor</option>
                                @foreach($availableInstructors as $instructor)
                                    <option value="{{ $instructor->id }}">{{ $instructor->firstname }} {{ $instructor->lastname }}</option>
                                @endforeach
                            </select>    --}}

                            @if(empty($assignment['instructor']))
                            <div class="text-gray-400">No Instructor Selected</div>
                            @else
                            <div class="text-[#2e3c75]">{{$assignment['instructor']}}</div>
                            @endif
                        </div>
                        <div class="w-6/12">
                            <button type="button" wire:click="openInstructorModal({{$index}})" class="import-form-add-button">Select Instructor</button>
                        </div>
                        <div class="w-3/12"></div>
                    </div>
                @endforeach
            @endif
        </div>
    </form>
    
    @if($showInstructorModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <x-custom-search-instructor-modal :availableInstructors="$availableInstructors" :filteredInstructors="$filteredInstructors" :selectedIndex="$selectedIndex"/>
    </div>
    @endif

    <div wire:loading wire:target="handleSubmit" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="text-white text-xl text-center m-80">Saving...</div>
    </div>

    @if(!$hasCourses) 
        <div class="flex flex-col items-center justify-center mt-10 text-gray-500">
            <div class="text-center text-4xl">Create a course section to assign it!</div>
            <button class="bg-white text-[#3B784F] border border-[#3B784F] py-2 px-4 mx-2 my-5 rounded-lg hover:bg-[#3B784F] hover:text-white" 
            onclick="location.href='{{ route('import') }}'">
                Create Course Section
            </button>
        </div>
    @endif

    @if(session('success'))
        @if($showModal) 
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                @include('components.import-modal', ['user' => $user, 'moreText' => 'Assign More'])
            </div>
        @endif
    @endif
</div>

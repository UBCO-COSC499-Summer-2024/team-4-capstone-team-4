<div>
    <form wire:submit.prevent="handleSubmit" class="import-form relative">
        <div class="header flex justify-between py-2 bg-gray-200">
            <div class="w-4/12 text-center px-1">Course</div>
            <div class="w-4/12 text-center px-1">Instructor</div>
            <div class="w-3/12 text-center px-1"></div>
        </div>

        {{-- <div class="import-input w-4/12">
            <select wire:model="rows.{{$index}}.area_id" class="p-1 w-full">
                <option value="">Select</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                @endforeach
            </select>
            @error('rows.'.$index.'.area_id')<span class="import-error">{{ $message }}</span>@enderror
        </div> --}}

     
        

        @foreach($assignments as $index => $assignment)
        <div class="import-form-row flex justify-between items-center p-2 border-b">
            <div class="import-input w-4/12">{{ $availableCourses->firstWhere('id', $assignment['course_section_id'])->name }}</div>
            <div class="import-input w-4/12">
                <select wire:model="assignments.{{ $index }}.instructor_id">
                    <option value="">Select Instructor</option>
                    @foreach($availableInstructors as $instructor)
                        <option value="{{ $instructor->id }}">{{ $instructor->firstname }} {{$instructor->lastname}}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-3/12"></div>
        </div>
        @endforeach
          
{{-- 
        @foreach ($availableInstructors as $instructor)
            <div>{{ $instructor->firstname }}</div>
        @endforeach

        @foreach ($availableCourses as $courses)
            <div>{{ $courses->name }}</div>
        @endforeach --}}

       
        <div class="mt-4 flex justify-end space-x-2">
            <button type="submit" class="bg-green-500 text-black p-2 rounded hover:bg-green-600">Save</button>
        </div>
    </form>
</div>

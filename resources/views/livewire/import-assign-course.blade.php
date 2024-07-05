<div>
    <form wire:submit.prevent="handleSubmit" class="import-form relative">
        <div class="header flex justify-between py-2 bg-gray-200">
            <div class="w-4/12 text-center px-1">Course</div>
            <div class="w-4/12 text-center px-1">Instructor</div>
            <div class="w-3/12 text-center px-1"></div>
        </div>

        @foreach($assignments as $index => $assignment)
        <div class="import-form-row flex justify-between items-center px-2 py-6 border-b">
            @php
                $course = $availableCourses->firstWhere('id', $assignment['course_section_id']);
            @endphp
            <div class="assign-input w-4/12 text-center">{{ $course->name }} {{ $course->section }} - {{ $course->year}}{{ $course->session}} Term {{ $course->term }}</div>
            <div class="assign-input w-4/12 text-center">
                <select wire:model="assignments.{{ $index }}.instructor_id" class="instructor-select" style="width: 100%">
                    <option value="">Select Instructor</option>
                    @foreach($availableInstructors as $instructor)
                        <option value="{{ $instructor->id }}">{{ $instructor->firstname }} {{ $instructor->lastname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-3/12"></div>
        </div>
        @endforeach
        
        <div class="mt-4 flex justify-end space-x-2">
            <button type="submit" class="bg-green-500 text-white p-2 rounded hover:bg-green-600">Save</button>
        </div>
    </form>

    <div wire:loading wire:target="handleSubmit" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="text-white text-xl text-center m-80">Saving...</div>
    </div>

    @if(session('success'))
        @if($showModal) 
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <x-import-modal moreText="Assign More"/>
        </div>
        @endif
    @endif
</div>

<script>
    // In your Javascript (external .js resource or <script> tag)
    // $(document).ready(function() {
    //     $('.instructor-select').select2();
    // });
</script>

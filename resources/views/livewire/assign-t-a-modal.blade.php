<div
    x-data="{
        activeTA: @entangle('activeTA'),
        selectedTAs: @entangle('selectedTAs').defer,
        selectedCourses: @entangle('selectedCourses').defer,
        activeInstructor: @entangle('activeInstructor'),
        showConfirmationModal: @entangle('showConfirmationModal').defer,
        showModal: @entangle('showModal').defer,
        tas: @entangle('tas').defer,
        selectTA: function (ta) {
            this.activeTA = ta;
            if (!this.selectedTAs.find(ta => ta.ta_id === ta.id)) {
                this.selectedTAs.push({
                    ta_id: ta.id,
                    instructor_id: null,
                    course_id: null,
                });
            } else {
                this.selectedTAs = this.selectedTAs.filter(ta => ta.ta_id !== ta.id);
            }
            console.log(this.selectedTAs, this.activeTA);
            $dispatch('select-ta', { taId: ta });
        },
        selectInstructor: function (instructor) {
            this.activeInstructor = instructor;
            if (!this.selectedInstructors.find(instructor => instructor.instructor_id === instructor.id)) {
                this.selectedInstructors.push({
                    instructor_id: instructor.id,
                    course_id: null,
                });
            } else {
                this.selectedInstructors = this.selectedInstructors.filter(instructor => instructor.instructor_id !== instructor.id);
            }
            console.log(this.activeInstructor, this.selectedInstructors);
            $dispatch('select-instructor', { instructorId: instructor });
        },
    }"
>
    <button id="assignTAButton" wire:click="openModal" class="px-5 py-2 mb-2 text-sm font-bold text-center rounded-lg ubc-blue hover:text-white focus:ring-1 focus:outline-none me-1">
        Assign TA
    </button>
    @if ($showModal)
        <div class="fixed inset-0 overflow-y-auto z-1004 modal-centered" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block w-full max-w-4xl overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:w-auto"> <!-- Adjusted modal width -->
                    <div class="p-4 bg-custom-green">
                        <h3 class="text-lg font-medium leading-6 text-white" id="modal-title">Assign TA to a Course</h3>
                    </div>
                    <div class="p-6 bg-white">
                        <form wire:submit.prevent="assignTA">
                            @csrf
                            <div id="taAssignContainer">
                                @foreach ($selectedTAs as $index => $selected)
                                    <div class="flex mb-4 space-x-4 taAssignBlock">
                                        <div class="flex-1">
                                            <label for="taSelect" class="block text-sm font-medium text-gray-700">Select TA</label>
                                            <select wire:model="selectedTAs.{{ $index }}.ta_id" id="taSelect" name="ta_id[]" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Select an option</option>
                                                @foreach ($tas as $ta)
                                                    <option
                                                        x-on:click="selectTA({{ $ta->id }})"
                                                        value="modal_ta_{{ $ta->id }}"
                                                        >{{ $ta->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <label for="instructorSelect" class="block text-sm font-medium text-gray-700">Select Instructor</label>
                                            <select wire:model="selectedTAs.{{ $index }}.instructor_id" id="instructorSelect" name="instructor_id[]" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Select an option</option>
                                                @foreach ($instructors as $instructor)
                                                    <option value="{{ $instructor->id }}"
                                                        x-on:click="selectInstructor({{ $instructor->id }})"
                                                        wire:key="modal_instructor_{{ $instructor->id }}"
                                                        >{{ $instructor->getName() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <label for="courseSelect" class="block text-sm font-medium text-gray-700">Select a Course</label>
                                            <select wire:model="selectedTAs.{{ $index }}.course_id" id="courseSelect" name="course_id[]" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Select an option</option>
                                                @if (isset($selectedCourses[$index]))
                                                    @foreach ($selectedCourses[$index] as $course)
                                                        <option
                                                            wire:key="modal_course_{{ $course['id'] }}"
                                                            @change="activeCourse = {{ $course['id'] }}"
                                                            value="{{ $course['id'] }}">{{ $course['formattedName'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse sm:space-x-reverse sm:space-x-4">
                                <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white border border-transparent rounded-md shadow-sm bg-custom-green hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Save
                                </button>
                                <button type="button" wire:click="closeModal" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                                <button type="button" wire:click="addMore" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                    Add More
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showConfirmationModal)
        <div class="fixed inset-0 overflow-y-auto z-100 modal-centered" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="p-4 bg-custom-green">
                        <h3 class="text-lg font-medium leading-6 text-white" id="modal-title">TAs Assigned Successfully</h3>
                    </div>
                    <div class="p-6 bg-white">
                        <p class="text-sm text-gray-700">The TAs have been assigned successfully. Would you like to assign more TAs or finish?</p>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse sm:space-x-reverse sm:space-x-4">
                        <button type="button" wire:click="closeConfirmationModal" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white border border-transparent rounded-md shadow-sm bg-custom-green hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            OK
                        </button>
                        <button type="button" wire:click="openModal" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Assign More
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

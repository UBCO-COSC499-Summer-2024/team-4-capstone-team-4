<div>
    <button id="assignTAButton" wire:click="openModal" class="ubc-blue hover:text-white focus:ring-1 focus:outline-none font-bold rounded-lg text-sm px-5 py-2 text-center me-1 mb-2">
        Assign TA
    </button>
    @if ($showModal)
        <div class="fixed z-1004 inset-0 overflow-y-auto modal-centered" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-auto w-full max-w-4xl"> <!-- Adjusted modal width -->
                    <div class="bg-custom-green p-4">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">Assign TA to a Course</h3>
                    </div>
                    <div class="bg-white p-6">
                        <form wire:submit.prevent="assignTA">
                            @csrf
                            <div id="taAssignContainer">
                                @foreach ($selectedTAs as $index => $selected)
                                    <div class="taAssignBlock flex space-x-4 mb-4">
                                        <div class="flex-1">
                                            <label for="taSelect" class="block text-sm font-medium text-gray-700">Select TA</label>
                                            <select wire:model="selectedTAs.{{ $index }}.ta_id" id="taSelect" name="ta_id[]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Select an option</option>
                                                @foreach ($tas as $ta)
                                                    <option value="{{ $ta->id }}">{{ $ta->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <label for="instructorSelect" class="block text-sm font-medium text-gray-700">Select Instructor</label>
                                            <select wire:model="selectedTAs.{{ $index }}.instructor_id" id="instructorSelect" name="instructor_id[]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Select an option</option>
                                                @foreach ($instructors as $instructor)
                                                    <option value="{{ $instructor->id }}">{{ $instructor->firstname }} {{ $instructor->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <label for="courseSelect" class="block text-sm font-medium text-gray-700">Select a Course</label>
                                            <select wire:model="selectedTAs.{{ $index }}.course_id" id="courseSelect" name="course_id[]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Select an option</option>
                                                @if (isset($selectedCourses[$index]))
                                                    @foreach ($selectedCourses[$index] as $course)
                                                        <option value="{{ $course['id'] }}">{{ $course['formattedName'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse sm:space-x-reverse sm:space-x-4">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-custom-green text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Save
                                </button>
                                <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                                <button type="button" wire:click="addMore" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
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
        <div class="fixed z-50 inset-0 overflow-y-auto modal-centered" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-custom-green p-4">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">TAs Assigned Successfully</h3>
                    </div>
                    <div class="bg-white p-6">
                        <p class="text-sm text-gray-700">The TAs have been assigned successfully. Would you like to assign more TAs or finish?</p>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse sm:space-x-reverse sm:space-x-4">
                        <button type="button" wire:click="closeConfirmationModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-custom-green text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            OK
                        </button>
                        <button type="button" wire:click="openModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Assign More
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

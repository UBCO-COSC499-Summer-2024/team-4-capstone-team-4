<div id="assignModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-custom-green p-4">
                <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">Assign TA to a Course</h3>
            </div>
            <div class="bg-white p-6">
                <form id="assignTAForm" method="POST" action="{{ route('assignTA') }}">
                    @csrf
                    <div id="taAssignContainer">
                        <div class="taAssignBlock">
                            <div class="mb-4">
                                <label for="taSelect" class="block text-sm font-medium text-gray-700">Select TA</label>
                                <select id="taSelect" name="ta_id[]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <!-- Dynamically populated with TAs -->
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="instructorSelect" class="block text-sm font-medium text-gray-700">Select Instructor</label>
                                <select id="instructorSelect" name="instructor_id[]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <!-- Dynamically populated with Instructors -->
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="courseSelect" class="block text-sm font-medium text-gray-700">Select a Course</label>
                                <select id="courseSelect" name="course_id[]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <!-- Dynamically populated with Courses -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse sm:space-x-reverse sm:space-x-4">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-custom-green text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm close-assign-modal-button">
                            Cancel
                        </button>
                        <button type="button" id="addMoreButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Add More
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="confirmationModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                <button id="okButton" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-custom-green text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    OK
                </button>
                <button id="assignMoreButton" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Assign More
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const assignTAButton = document.getElementById('assignTAButton');
        const taSelect = document.getElementById('taSelect');
        const instructorSelect = document.getElementById('instructorSelect');
        const courseSelect = document.getElementById('courseSelect');
        const assignModal = document.getElementById('assignModal');
        const closeModalButtons = document.querySelectorAll('.close-assign-modal-button, .close-assign-modal');
        const assignTAForm = document.getElementById('assignTAForm');
        const addMoreButton = document.getElementById('addMoreButton');
        const taAssignContainer = document.getElementById('taAssignContainer');
        const confirmationModal = document.getElementById('confirmationModal');
        const okButton = document.getElementById('okButton');
        const assignMoreButton = document.getElementById('assignMoreButton');

        function openAssignModal() {
            assignModal.classList.remove('hidden');
            fetchAndPopulateSelect('/api/teaching-assistants', taSelect, item => item.name);
            fetchAndPopulateSelect('/api/instructors', instructorSelect, item => `${item.firstname} ${item.lastname}`);
        }

        function closeAssignModal() {
            assignModal.classList.add('hidden');
        }

        function fetchAndPopulateSelect(url, selectElement, formatDataFn = null) {
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (!Array.isArray(data)) {
                    throw new Error('Expected an array but did not receive one');
                }
                selectElement.innerHTML = '<option value="">Select an option</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = formatDataFn ? formatDataFn(item) : item.name;
                    selectElement.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching data:', error));
        }

        function fetchCoursesByInstructor(instructorId) {
            const url = `/api/courses/instructor/${instructorId}`;
            fetchAndPopulateSelect(url, courseSelect, item => `${item.prefix} ${item.number} ${item.section} - ${item.year}${item.session} ${item.term}`);
        }

        if (assignTAButton) {
            assignTAButton.addEventListener('click', openAssignModal);
        }

        if (closeModalButtons.length > 0) {
            closeModalButtons.forEach(button => {
                button.addEventListener('click', closeAssignModal);
            });
        }

        if (instructorSelect) {
            instructorSelect.addEventListener('change', function() {
                const instructorId = this.value;
                if (instructorId) {
                    fetchCoursesByInstructor(instructorId);
                } else {
                    courseSelect.innerHTML = '<option value="">Select a Course</option>';
                }
            });
        }

        addMoreButton.addEventListener('click', function() {
            const newBlock = document.querySelector('.taAssignBlock').cloneNode(true);
            taAssignContainer.appendChild(newBlock);
        });

        assignTAForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(assignTAForm);
            fetch(assignTAForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                closeAssignModal();
                confirmationModal.classList.remove('hidden');
            })
            .catch(error => console.error('Error submitting form:', error));
        });

        okButton.addEventListener('click', function() {
            location.reload();
        });

        assignMoreButton.addEventListener('click', function() {
            confirmationModal.classList.add('hidden');
            openAssignModal();
        });
    });
</script>

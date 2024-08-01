<div id="seiEditModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-custom-green p-4 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-white whitespace-nowrap" id="modal-title">Edit SEI Data</h3>
                <button onclick="closeSeiModal()" class="text-white font-bold">&times;</button>
            </div>
            <div class="bg-white p-6">
                <form id="seiEditForm" method="POST" action="{{ route('sei.manage') }}">
                    @csrf
                    <div id="seiDataRows">
                        <div class="grid grid-cols-6 gap-4 mb-4">
                            <div class="col-span-6">
                                <label for="courseSelect" class="block text-sm font-medium text-gray-700">Select Course Section</label>
                                <select id="courseSelect" name="course_id[]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select a course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @for ($i = 1; $i <= 6; $i++)
                                <div>
                                    <label for="q{{ $i }}" class="block text-sm font-medium text-gray-700">Q{{ $i }} (IM)</label>
                                    <input type="text" name="q{{ $i }}[]" id="q{{ $i }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md input-bordered" />
                                </div>
                            @endfor
                        </div>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" id="addRow" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Add Row
                        </button>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    console.log('JavaScript loaded'); // Initial log to ensure script is loaded

function openSeiModal() {
    console.log('Opening modal...'); // Log opening modal
    resetSeiModal(); 
    document.getElementById('seiEditModal').classList.remove('hidden');
}

function closeSeiModal() {
    console.log('Closing modal...'); // Log closing modal
    document.getElementById('seiEditModal').classList.add('hidden');
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
    if (event.target === document.getElementById('seiEditModal')) {
        closeSeiModal();
    }
}

// Fetch SEI data when course section is selected
document.getElementById('courseSelect').addEventListener('change', function(event) {
    const courseId = event.target.value;
    console.log(`Course selected: ${courseId}`); // Log course selection
    if (courseId) {
        console.log(`Fetching SEI data for course ID: ${courseId}`);
        fetch(`/courses/sei-data/${courseId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Received SEI data:', data); // Log received data
                populateSeiData(data);
            })
            .catch(error => console.error('Error fetching SEI data:', error));
    } else {
        resetSeiModal();
    }
});

function populateSeiData(data) {
    console.log('Populating SEI data...', data); // Log start of population
    for (let i = 1; i <= 6; i++) {
        const input = document.querySelector(`input[name="q${i}[]"]`);
        if (input) {
            input.value = data[`q${i}`] || '';
            console.log(`Populated Q${i} with: ${data[`q${i}`]}`); // Log populated value
        } else {
            console.error(`Input for Q${i} not found`);
        }
    }
    console.log('SEI data populated.'); // Log completion
}

// Add new row functionality
document.getElementById('addRow').addEventListener('click', function(event) {
    event.preventDefault();
    console.log('Adding new row...'); // Log new row addition

    const seiDataRows = document.getElementById('seiDataRows');
    const newRow = document.createElement('div');
    newRow.className = 'grid grid-cols-6 gap-4 mb-4';

    // Create the course section dropdown
    const courseSelectContainer = document.createElement('div');
    courseSelectContainer.className = 'col-span-6';

    const courseLabel = document.createElement('label');
    courseLabel.className = 'block text-sm font-medium text-gray-700';
    courseLabel.innerText = 'Select Course Section';

    const courseSelect = document.createElement('select');
    courseSelect.name = 'course_id[]';
    courseSelect.className = 'mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm';

    const courses = @json($courses); // Convert Blade variable to JavaScript
    courses.forEach(course => {
        const option = document.createElement('option');
        option.value = course.id;
        option.text = course.name;
        courseSelect.appendChild(option);
    });

    courseSelectContainer.appendChild(courseLabel);
    courseSelectContainer.appendChild(courseSelect);
    newRow.appendChild(courseSelectContainer);

    // Create the question input fields
    for (let i = 1; i <= 6; i++) {
        const newField = document.createElement('div');
        const label = document.createElement('label');
        label.htmlFor = 'q' + i;
        label.className = 'block text-sm font-medium text-gray-700';
        label.innerText = 'Q' + i + ' (IM)';

        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'q' + i + '[]';
        input.id = 'q' + i;
        input.className = 'mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md input-bordered'; // Add 'input-bordered' class

        newField.appendChild(label);
        newField.appendChild(input);
        newRow.appendChild(newField);
    }

    seiDataRows.appendChild(newRow);
    console.log('New row added:', newRow); // Log new row addition
});

document.getElementById('seiEditForm').addEventListener('submit', function(event) {
    event.preventDefault();
    console.log('Submitting form...'); // Log form submission

    const formData = new FormData(this);

    fetch(`/courses/sei-data`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
    })
    .then(response => response.json())
    .then(data => {
        console.log('Form submission response:', data); // Log form submission response
        alert(data.message);
        closeSeiModal();
    })
    .catch(error => console.error('Error submitting form:', error));
});

function resetSeiModal() {
    console.log('Resetting modal...'); // Log modal reset
    const seiDataRows = document.getElementById('seiDataRows');
    seiDataRows.innerHTML = `
        <div class="grid grid-cols-6 gap-4 mb-4">
            <div class="col-span-6">
                <label for="courseSelect" class="block text-sm font-medium text-gray-700">Select Course Section</label>
                <select name="course_id[]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Select a course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            @for ($i = 1; $i <= 6; $i++)
                <div>
                    <label for="q{{ $i }}" class="block text-sm font-medium text-gray-700">Q{{ $i }} (IM)</label>
                    <input type="text" name="q{{ $i }}[]" id="q{{ $i }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md input-bordered" />
                </div>
            @endfor
        </div>
    `;
    console.log('Modal reset complete.');
}

</script>
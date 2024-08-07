document.addEventListener('DOMContentLoaded', function () {
    const assignTAButton = document.getElementById('assignTAButton');
    const taSelect = document.getElementById('taSelect');
    const instructorSelect = document.getElementById('instructorSelect');
    const courseSelect = document.getElementById('courseSelect');
    const assignModal = document.getElementById('assignModal');
    const confirmationModal = document.getElementById('confirmationModal');
    const closeModalButtons = document.querySelectorAll('.close-assign-modal-button, .close-assign-modal');
    const assignTAForm = document.getElementById('assignTAForm');
    const okButton = document.getElementById('okButton');
    const assignMoreButton = document.getElementById('assignMoreButton');

    function openAssignModal() {
        console.log("Opening assign TA modal");
        assignModal.classList.remove('hidden');
        fetchAndPopulateSelect('/api/teaching-assistants', taSelect, item => item.name);
        fetchAndPopulateSelect('/api/instructors', instructorSelect, item => `${item.firstname} ${item.lastname}`);
    }

    function closeAssignModal() {
        console.log("Closing assign TA modal");
        assignModal.classList.add('hidden');
    }

    function openConfirmationModal() {
        console.log("Opening confirmation modal");
        confirmationModal.classList.remove('hidden');
    }

    function closeConfirmationModal() {
        console.log("Closing confirmation modal");
        confirmationModal.classList.add('hidden');
    }

    function fetchAndPopulateSelect(url, selectElement, formatDataFn = null) {
        console.log(`Fetching data from: ${url}`);
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
            console.log(`Received data:`, data);
            if (!Array.isArray(data)) {
                throw new Error('Expected an array but did not receive one');
            }
            selectElement.innerHTML = '<option value="">Select an option</option>';
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = formatDataFn ? formatDataFn(item) : item.name;
                console.log(`Appending option: ${option.textContent}`);
                selectElement.appendChild(option);
            });
            console.log(`Options appended to ${selectElement.id}:`, selectElement.innerHTML);
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    function fetchCoursesByInstructor(instructorId) {
        const url = `/api/courses/instructor/${instructorId}`;
        console.log(`Fetching courses for instructor ID: ${instructorId}`);
        fetchAndPopulateSelect(url, courseSelect, item => `${item.prefix} ${item.number} ${item.section} - ${item.year}${item.session} ${item.term}`);
    }

    if (assignTAButton) {
        assignTAButton.addEventListener('click', openAssignModal);
    } else {
        // console.error('assignTAButton is null');
    }

    if (closeModalButtons.length > 0) {
        closeModalButtons.forEach(button => {
            button.addEventListener('click', closeAssignModal);
        });
    } else {
        // console.error('closeModalButtons are null or empty');
    }

    if (instructorSelect) {
        instructorSelect.addEventListener('change', function() {
            const instructorId = this.value;
            console.log(`Instructor selected: ${instructorId}`);
            if (instructorId) {
                fetchCoursesByInstructor(instructorId);
            } else {
                courseSelect.innerHTML = '<option value="">Select a Course</option>';
            }
        });
    } else {
        // console.error('instructorSelect is null');
    }

    if (!assignTAForm) {
        // console.error('assignTAForm is null');
        return;
    }

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
            console.log('Form submitted successfully:', data);
            closeAssignModal();
            openConfirmationModal();
        })
        .catch(error => console.error('Error submitting form:', error));
    });

    if (okButton) {
        okButton.addEventListener('click', function() {
            closeConfirmationModal();
            location.reload();
        });
    }

    if (!assignMoreButton) {
        return;
    }

    assignMoreButton.addEventListener('click', function() {
        closeConfirmationModal();
        openAssignModal();
    });
});

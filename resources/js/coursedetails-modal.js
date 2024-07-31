document.addEventListener('DOMContentLoaded', function () {
    const assignTAButton = document.getElementById('assignTAButton');
    const taSelect = document.getElementById('taSelect');
    const courseSelect = document.getElementById('courseSelect');
    const assignModal = document.getElementById('assignModal');

    function openAssignModal() {
        assignModal.classList.remove('hidden');
    }

    function closeAssignModal() {
        assignModal.classList.add('hidden');
    }

    function fetchAndPopulateSelect(url, selectElement) {
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
                option.textContent = item.name || item.course_name;
                selectElement.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    if (assignTAButton) {
        assignTAButton.addEventListener('click', openAssignModal);
    }

    // Fetch data for TA and Courses and populate selects
    if (taSelect && courseSelect) {
        fetchAndPopulateSelect('/api/teaching-assistants', taSelect);
        fetchAndPopulateSelect('/api/courses', courseSelect);
    }
});

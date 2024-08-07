document.addEventListener('DOMContentLoaded', function () {
    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');
    const coursesTable = document.querySelector('#coursesTable tbody');
    const form = document.getElementById('editForm');
    const instructorFilter = document.getElementById('instructorFilter');
    const courseDetailsTab = document.getElementById('courseDetailsTab');
    const tasTab = document.getElementById('tasTab');

    function toggleButtonVisibility(buttonToHide, reverse = false) {
        const buttons = [editButton, saveButton, cancelButton];
        buttons.forEach(button => {
            if (button) {
                if (reverse) {
                    button.style.display = (button === buttonToHide) ? 'block' : 'none';
                } else {
                    button.style.display = (button === buttonToHide) ? 'none' : 'block';
                }
            }
        });
    }

    function enableEditing() {
        document.querySelectorAll('#coursesTable tbody tr').forEach(row => {
            row.querySelectorAll('td').forEach((cell, index) => {
                if ([4, 5, 6].includes(index)) {
                    cell.setAttribute('contenteditable', 'true');
                    cell.classList.add('edit-highlight');
                }
            });
        });
        toggleButtonVisibility(editButton);
    }

    function disableEditing() {
        coursesTable.querySelectorAll('tr').forEach(row => {
            row.querySelectorAll('td').forEach(cell => {
                cell.setAttribute('contenteditable', 'false');
                cell.classList.remove('edit-highlight');
                cell.classList.remove('error-input');
            });
        });
        toggleButtonVisibility(editButton, true);
    }

    function validateInput() {
        let isValid = true;
        const rows = document.querySelectorAll('#coursesTable tbody tr');
        rows.forEach(row => {
            row.querySelectorAll('td').forEach((cell, index) => {
                if ([4, 5, 6].includes(index)) {
                    const value = cell.innerText.trim();
                    if (isNaN(value) || value === '') {
                        cell.classList.add('error-input');
                        isValid = false;
                    } else {
                        cell.classList.remove('error-input');
                    }
                }
            });
            // Check if capacity is greater than enrolled
            const enrolledStudents = row.children[3]?.innerText.trim();
            const courseCapacities = row.children[5]?.innerText.trim();
            if (!isNaN(enrolledStudents) && !isNaN(courseCapacities) && enrolledStudents !== '' && courseCapacities !== '') {
                if (parseInt(enrolledStudents) > parseInt(courseCapacities)) {
                    row.children[6].classList.add('error-input');
                    row.children[4].classList.add('error-input');
                    isValid = false;
                } else {
                    row.children[6].classList.remove('error-input');
                    row.children[4].classList.remove('error-input');
                }
            }
        });
        return isValid;
    }
    

    function saveChanges() {
        // Validate input
        if (!validateInput()) {
            alert('Please enter valid numeric values in the editable fields.');
            return;
        }
    
        // Confirm save action
        const confirmSave = confirm('Do you really want to save the changes?');
        if (!confirmSave) return;
    
        // Select the form element and ensure it's defined
        const form = document.querySelector('form');
        if (!form) {
            console.error('Form element not found.');
            return;
        }
    
        // Get all rows from the courses table
        const rows = document.querySelectorAll('#coursesTable tbody tr');
        const formData = new FormData();
    
        // Collect data from each row
        rows.forEach(row => {
            formData.append('ids[]', row.getAttribute('data-id'));
            formData.append('courseNames[]', row.children[0]?.innerText.trim().split(' - ')[0] || '');
            formData.append('enrolledStudents[]', row.children[4]?.innerText.trim() || '');
            formData.append('droppedStudents[]', row.children[5]?.innerText.trim() || '');
            formData.append('courseCapacities[]', row.children[6]?.innerText.trim() || '');
        });
    
        console.log('Form Data:', Array.from(formData.entries()));
    
        // Fetch the CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (!csrfToken) {
            console.error('CSRF token not found.');
            return;
        }
    
        // Perform the fetch request
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(result => {
            console.log('Server Response:', result);
            if (result.message === 'Courses updated successfully.') {
                alert('Successfully Saved!');
                disableEditing();
    
                result.updatedSections.forEach(updatedSection => {
                    const row = document.querySelector(`tr[data-id="${updatedSection.id}"]`);
                    if (row) {
                        row.children[0].innerText = `${updatedSection.prefix} ${updatedSection.number} ${updatedSection.section} - ${updatedSection.year}${updatedSection.session} ${updatedSection.term}`;
                        row.children[4].innerText = updatedSection.enrolled;
                        row.children[5].innerText = updatedSection.dropped;
                        row.children[6].innerText = updatedSection.capacity;
                    }
                });
    
                // Hide save and cancel buttons, show edit button
                document.getElementById('saveButton').style.display = 'none';
                document.getElementById('cancelButton').style.display = 'none';
                document.getElementById('editButton').style.display = 'block';
            } else {
                console.error('Save failed.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    

    function checkTab() {
        if (tasTab && tasTab.classList.contains('active')) {
            if (editButton) {
                editButton.style.display = 'none';
            }
            if (saveButton) {
                saveButton.style.display = 'none';
            }
            if (cancelButton) {
                cancelButton.style.display = 'none';
            }
        } else {
            if (editButton) {
                editButton.style.display = 'block';
            }
        }
    }

    if (editButton) {
        editButton.addEventListener('click', enableEditing);
    }

    if (cancelButton) {
        cancelButton.addEventListener('click', disableEditing);
    }

    if (saveButton) {
        saveButton.addEventListener('click', saveChanges);
    }

    if (instructorFilter) {
        instructorFilter.addEventListener('change', function () {
            const selectedInstructorId = this.value;
            const url = new URL(window.location.href);
            if (selectedInstructorId) {
                url.searchParams.set('instructor_id', selectedInstructorId);
            } else {
                url.searchParams.delete('instructor_id');
            }
            window.location.href = url.toString();
        });
    }

    if (courseDetailsTab && tasTab) {
        courseDetailsTab.addEventListener('click', checkTab);
        tasTab.addEventListener('click', checkTab);
    }

    // Initial check on page load
    checkTab();
});

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
                if ([3, 4, 5].includes(index)) {
                    cell.setAttribute('contenteditable', 'true');
                    cell.classList.add('editable-highlight');
                }
            });
        });
        toggleButtonVisibility(editButton);
    }

    function disableEditing() {
        coursesTable.querySelectorAll('tr').forEach(row => {
            row.querySelectorAll('td').forEach(cell => {
                cell.setAttribute('contenteditable', 'false');
                cell.classList.remove('editable-highlight');
                cell.classList.remove('input-error');
            });
        });
        toggleButtonVisibility(editButton, true);
    }

    function validateInput() {
        let isValid = true;
        const rows = document.querySelectorAll('#coursesTable tbody tr');
        rows.forEach(row => {
            const enrolledCell = row.children[3];
            const capacityCell = row.children[5];
            const enrolledValue = parseInt(enrolledCell.innerText.trim());
            const capacityValue = parseInt(capacityCell.innerText.trim());

            if (isNaN(enrolledValue) || isNaN(capacityValue) || enrolledValue > capacityValue) {
                enrolledCell.classList.add('input-error');
                capacityCell.classList.add('input-error');
                isValid = false;
            } else {
                enrolledCell.classList.remove('input-error');
                capacityCell.classList.remove('input-error');
            }
        });
        return isValid;
    }

    function showErrorModal(message) {
        const errorModal = document.getElementById('errorModal');
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.innerText = message;
        errorModal.classList.remove('hidden');
    }

    function saveChanges() {
        if (!validateInput()) {
            showErrorModal('Capacity cannot be less than enrolled students.');
            return;
        }

        const confirmSave = confirm('Do you really want to save the changes?');
        if (!confirmSave) return;

        const rows = document.querySelectorAll('#coursesTable tbody tr');
        const formData = new FormData();

        rows.forEach(row => {
            formData.append('ids[]', row.getAttribute('data-id'));
            formData.append('courseNames[]', row.children[0]?.innerText.trim().split(' - ')[0] || '');
            formData.append('enrolledStudents[]', row.children[2]?.innerText.trim() || '');
            formData.append('droppedStudents[]', row.children[3]?.innerText.trim() || '');
            formData.append('courseCapacities[]', row.children[4]?.innerText.trim() || '');
        });

        console.log('Form Data:', Array.from(formData.entries()));

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                        row.children[2].innerText = updatedSection.enrolled;
                        row.children[3].innerText = updatedSection.dropped;
                        row.children[4].innerText = updatedSection.capacity;
                    }
                });

                saveButton.style.display = 'none';
                cancelButton.style.display = 'none';
                editButton.style.display = 'block';
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

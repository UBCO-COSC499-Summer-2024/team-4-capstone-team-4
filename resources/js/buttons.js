document.addEventListener('DOMContentLoaded', function () {
    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');
    const table = document.querySelector('tbody');
    const form = document.getElementById('editForm');

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
            document.querySelectorAll('tbody tr').forEach(row => {
                row.querySelectorAll('td').forEach((cell, index) => {
                    if ([2, 3, 4].includes(index)) {
                        cell.setAttribute('contenteditable', 'true');
                        cell.classList.add('editable-highlight');
                    }
                });
        toggleButtonVisibility(editButton);
    });
}


    function disableEditing() {
        table.querySelectorAll('tr').forEach(row => {
            row.querySelectorAll('td').forEach(cell => {
                cell.setAttribute('contenteditable', 'false');
                cell.classList.remove('editable-highlight');

            });
        });
        toggleButtonVisibility(editButton, true);
    }

    function saveChanges() {
        const confirmSave = confirm('Do you really want to save the changes?');
        if (!confirmSave) return;

        const rows = document.querySelectorAll('tbody tr');
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

    if (editButton) {
        editButton.addEventListener('click', enableEditing);
    }

    if (cancelButton) {
        cancelButton.addEventListener('click', disableEditing);
    }

    if (saveButton) {
        saveButton.addEventListener('click', saveChanges);
    }
});

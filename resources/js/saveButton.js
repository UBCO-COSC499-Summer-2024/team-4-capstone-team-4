document.addEventListener('DOMContentLoaded', function () {
    const saveButton = document.getElementById('saveButton');
    const form = document.getElementById('editForm');
    const editButton = document.getElementById('editButton'); 
    const cancelButton = document.getElementById('cancelButton');


    if(saveButton){
    saveButton.addEventListener('click', function () {
        const confirmSave = confirm('Do you really want to save the changes?');

        if (confirmSave) {
            const rows = document.querySelectorAll('tbody tr');
            const formData = new FormData(); // Create a new FormData object

            rows.forEach(row => {
                formData.append('ids[]', row.getAttribute('data-id'));
                formData.append('courseNames[]', row.children[0]?.innerText.trim().split(' - ')[0] || '');
                formData.append('enrolledStudents[]', row.children[2]?.innerText.trim() || '');
                formData.append('droppedStudents[]', row.children[3]?.innerText.trim() || '');
                formData.append('courseCapacities[]', row.children[4]?.innerText.trim() || '');
            });

            console.log('Form Data:', Array.from(formData.entries())); // Log form data for debugging

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
                return response.json(); // Expecting JSON response
            })
            .then(result => {
                console.log('Server Response:', result); // Log server response for debugging
                if (result.message === 'Courses updated successfully.') {
                    alert('Successfully Saved!');

                    document.querySelectorAll('td[contenteditable="true"]').forEach(td => {
                        td.setAttribute('contenteditable', 'false');
                    });

                    saveButton.style.display = 'none';
                    cancelButton.style.display = 'none';
                    editButton.style.display = 'block';

                    // Update the rows based on the updated data from the server
                    result.updatedSections.forEach(updatedSection => {
                        const row = document.querySelector(`tr[data-id="${updatedSection.id}"]`);
                        if (row) {
                            row.children[0].innerText = `${updatedSection.prefix} ${updatedSection.number} ${updatedSection.section} - ${updatedSection.year}${updatedSection.session} ${updatedSection.term}`;
                            row.children[2].innerText = updatedSection.enrolled;
                            row.children[3].innerText = updatedSection.dropped;
                            row.children[4].innerText = updatedSection.capacity;
                        }
                    });
                } else {
                    console.error('Save failed.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });
}
});

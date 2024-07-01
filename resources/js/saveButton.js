document.addEventListener('DOMContentLoaded', function () {
    const saveButton = document.getElementById('saveButton');
    const form = document.getElementById('editForm');

    saveButton.addEventListener('click', function () {
        const confirmSave = confirm('Do you really want to save the changes?');

        if (confirmSave) {
            const rows = document.querySelectorAll('tbody tr');
            const formData = new FormData(form); // Create a new FormData object

            rows.forEach(row => {
                formData.append('ids[]', row.getAttribute('data-id'));
                formData.append('serialNumbers[]', row.children[0].innerText.trim());
                formData.append('courseNames[]', row.children[1].innerText.trim());
                formData.append('departmentNames[]', row.children[2].innerText.trim());
                formData.append('courseDurations[]', row.children[3].innerText.trim());
                formData.append('enrolledStudents[]', row.children[4].innerText.trim());
                formData.append('droppedStudents[]', row.children[5].innerText.trim());
                formData.append('courseCapacities[]', row.children[6].innerText.trim());
            });

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
                if (result.success) {
                    alert('Successfully Saved!');

                    document.querySelectorAll('td[contenteditable="true"]').forEach(td => {
                        td.setAttribute('contenteditable', 'false');
                    });

                    saveButton.style.display = 'none';
                    cancelButton.style.display = 'none';
                    editButton.style.display = 'block';
                } else {
                    console.error(result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });
});

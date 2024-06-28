document.addEventListener('DOMContentLoaded', function () {
    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');

    editButton.addEventListener('click', function () {
        // Enable contenteditable on all table cells
        document.querySelectorAll('td[contenteditable="false"]').forEach(td => {
            td.setAttribute('contenteditable', 'true');
        });

        // Show the save button
        saveButton.style.display = 'block';
        cancelButton.style.display='block';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');

    editButton.addEventListener('click', function () {
        document.querySelectorAll('td[contenteditable="false"]').forEach(td => {
            td.setAttribute('contenteditable', 'true');
        });

        saveButton.style.display = 'block';
        cancelButton.style.display = 'block';
        editButton.style.display = 'none';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');

<<<<<<< HEAD
    editButton.addEventListener('click', function () {
        document.querySelectorAll('td[contenteditable="false"]').forEach(td => {
            td.setAttribute('contenteditable', 'true');
        });

        saveButton.style.display = 'block';
        cancelButton.style.display = 'block';
        editButton.style.display = 'none';
    });
=======
    if (editButton) {
        editButton.addEventListener('click', function () {
            document.querySelectorAll('td[contenteditable="false"]').forEach(td => {
                td.setAttribute('contenteditable', 'true');
            });

            editButton.style.display = 'none';
            if (saveButton) saveButton.style.display = 'block';
            if (cancelButton) cancelButton.style.display = 'block';
        });
    }
>>>>>>> origin/pre-dev-integration
});

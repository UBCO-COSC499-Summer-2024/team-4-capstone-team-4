document.addEventListener('DOMContentLoaded', function () {
    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');

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
});

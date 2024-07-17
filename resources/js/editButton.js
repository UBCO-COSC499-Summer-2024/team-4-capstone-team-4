document.addEventListener('DOMContentLoaded', function () {
    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');

    if (editButton) {
        editButton.addEventListener('click', function () {
            document.querySelectorAll('tbody tr').forEach(row => {
                row.querySelectorAll('td').forEach((cell, index) => {
                    if ([2, 3, 4].includes(index)) { // Only enable editing for columns 3, 4, and 5
                        cell.setAttribute('contenteditable', 'true');
                    }
                });
            });

            editButton.style.display = 'none';
            if (saveButton) saveButton.style.display = 'block';
            if (cancelButton) cancelButton.style.display = 'block';
        });
    }

    if (cancelButton) {
        cancelButton.addEventListener('click', function () {
            document.querySelectorAll('tbody tr').forEach(row => {
                row.querySelectorAll('td').forEach(cell => {
                    cell.setAttribute('contenteditable', 'false');
                });
            });

            editButton.style.display = 'block';
            if (saveButton) saveButton.style.display = 'none';
            cancelButton.style.display = 'none';
        });
    }
});

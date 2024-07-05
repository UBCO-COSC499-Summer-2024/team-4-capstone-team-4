document.addEventListener('DOMContentLoaded', function () {
    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');
    const editButton = document.getElementById('editButton');
    const table = document.querySelector('tbody');

    cancelButton.addEventListener('click', function () {
        table.querySelectorAll('tr').forEach((row, index) => {
            row.querySelectorAll('td').forEach(cell => {
                cell.setAttribute('contenteditable', 'false');
            });
        });

        saveButton.style.display = 'none';
        cancelButton.style.display = 'none';
        editButton.style.display = 'block';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const saveButton = document.getElementById('save-button');
    const cancelButton=document.getElementById('cancel-button');
    const table=document.querySelector('tbody');

    cancelButton.addEventListener('click', function(){

        saveButton.classList.remove('hidden');
        cancelButton.classList.remove('hidden');
        editButton.classList.add('hidden');

    });
});

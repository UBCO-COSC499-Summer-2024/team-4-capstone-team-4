document.addEventListener('DOMContentLoaded', function () {
    const saveButton = document.getElementById('saveButton');
    const cancelButton=document.getElementById('cancelButton');
    const table=document.querySelector('tbody');

    cancelButton.addEventListener('click', function(){

        document.querySelectorAll('td[contenteditable="true"]').forEach(td => {
            td.setAttribute('contenteditable', 'false');
        });

        saveButton.style.display='none';
        cancelButton.style.display='none';

    });
});

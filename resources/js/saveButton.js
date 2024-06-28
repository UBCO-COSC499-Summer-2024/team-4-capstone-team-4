document.addEventListener('DOMContentLoaded', function () {
    const saveButton = document.getElementById('saveButton');
    const form = document.getElementById('editForm');

    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission

        const rows = document.querySelectorAll('tbody tr');
        const data = [];

        rows.forEach(row => {
            const rowData = {
                id: row.getAttribute('data-id'),
                serialNumber: row.children[0].innerText.trim(),
                courseName: row.children[1].innerText.trim(),
                departmentName: row.children[2].innerText.trim(),
                courseDuration: row.children[3].innerText.trim(),
                enrolledStudents: row.children[4].innerText.trim(),
                droppedStudents: row.children[5].innerText.trim(),
                courseCapacity: row.children[6].innerText.trim()
            };
            data.push(rowData);
        });

        // Send the data to the server
        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ data })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Display success message
                alert('Successfully Saved!'); // Replace with your success message modal logic

                // Disable contenteditable
                document.querySelectorAll('td[contenteditable="true"]').forEach(td => {
                    td.setAttribute('contenteditable', 'false');
                });

                // Hide the save button
                saveButton.style.display = 'none';
            } else {
                // Handle errors
                console.error(result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});

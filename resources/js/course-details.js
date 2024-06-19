document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('.CourseDetailsController');
    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const user_id = this.getAttribute('data-id');

            fetch(`/users/${user_id}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('.coursedetails-table tbody');
                    tableBody.innerHTML = '';

                    data.forEach(course => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td>${course['Course Name']}</td>
                        <td>${course['Course Duration']}</td>
                        <td>${course['Enrolled Students']}</td>
                        <td>${course['Dropped Students']}</td>
                        <td>${course['Course Capacity']}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                });
        });
    });
});
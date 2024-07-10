document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value;

            fetch(`/course-details/search?query=${query}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const tbody = document.querySelector('tbody');
                tbody.innerHTML = '';

                data.courseSections.forEach(section => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-id', section.id);
                    row.innerHTML = `
                        <td>${section.name}</td>
                        <td>${section.departmentName}</td>
                        <td>${section.enrolled}</td>
                        <td>${section.dropped}</td>
                        <td>${section.capacity}</td>
                        <td>${section.averageRating}</td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    } else {
        console.error('searchInput element not found');
    }
});

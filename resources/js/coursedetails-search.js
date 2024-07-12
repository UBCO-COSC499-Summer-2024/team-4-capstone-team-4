document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('courseTableBody'); // Ensure this ID matches the table body ID
    const courseDetailsRoute = searchInput.getAttribute('data-route');

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.trim();
        fetch(`${courseDetailsRoute}?search=${query}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            tableBody.innerHTML = '';
            if (data.length > 0) {
                data.forEach(section => {
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

                    tableBody.appendChild(row);
                });
            } else {
                const row = document.createElement('tr');
                row.innerHTML = `<td colspan="7" class="text-center text-gray-500 py-4">No course sections found.</td>`;
                tableBody.appendChild(row);
            }
        })
        .catch(error => console.error('Error fetching search results:', error));
    });
});

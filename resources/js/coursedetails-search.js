document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('courseTableBody'); // Ensure this ID matches the table body ID
    if (!searchInput || !tableBody) return; // Exit if elements are not found
    const courseDetailsRoute = searchInput.getAttribute('data-route');

    if (!searchInput || !tableBody) return;

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
                        <td class="px-6 py-4 whitespace-nowrap">${section.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${section.departmentName}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${section.enrolled}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${section.dropped}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${section.capacity}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${section.averageRating}</td>
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

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('courseTableBody');
    const instructorFilter = document.getElementById('instructorFilter'); // Get the instructor filter element

    if (!searchInput || !tableBody) return;
    const courseDetailsRoute = searchInput.getAttribute('data-route');

    function fetchCourses(query, instructorId) {
        const url = new URL(courseDetailsRoute);
        url.searchParams.append('search', query);
        if (instructorId) {
            url.searchParams.append('instructor_id', instructorId);
        }
        fetch(url.toString(), {
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
    }

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.trim();
        const instructorId = instructorFilter.value; // Get the selected instructor ID
        fetchCourses(query, instructorId);
    });

    instructorFilter.addEventListener('change', function () {
        const query = searchInput.value.trim();
        const instructorId = instructorFilter.value;
        fetchCourses(query, instructorId);
    });
});

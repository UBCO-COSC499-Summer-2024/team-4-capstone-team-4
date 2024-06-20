document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.instructor-link').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            let instructorId = this.dataset.id;
            fetch(`/courses/${instructorId}`)
                .then(response => response.json())
                .then(data => {
                    // Clear existing table data
                    let tbody = document.querySelector('.coursedetails-table tbody');
                    tbody.innerHTML = '';

                    // Populate table with new data
                    data.forEach(item => {
                        let row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">${item['Course Name']}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${item['Course Duration']}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${item['Enrolled Students']}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${item['Dropped Students']}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${item['Course Capacity']}</td>
                        `;
                        tbody.appendChild(row);
                    });
                });
        });
    });
});
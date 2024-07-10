<div class="flex items-center space-x-4 ml-auto">
    <input type="text" id="courseSearchInput" placeholder="Search courses..." class="border rounded-lg py-2 px-4">
</div>

<script>
   document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('courseSearchInput');
    searchInput.addEventListener('input', function () {
        const query = searchInput.value;
        fetch(`/course-details/search?query=${query}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('tbody');
                tableBody.innerHTML = '';
                data.forEach(section => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-id', section.id);
                    row.innerHTML = `
                        <td>${section.name}</td>
                        <td>${section.departmentName}</td>
                        <td contenteditable="true">${section.enrolled}</td>
                        <td contenteditable="true">${section.dropped}</td>
                        <td contenteditable="true">${section.capacity}</td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error:', error));
    });
});
</script>

window.onload = function() {
    document.getElementById('staff-select-all').addEventListener('change', function(event) {
        var checkboxes = document.querySelectorAll('.staff-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = event.target.checked;
        });
    });

    var filterButton = document.getElementById('filterButton');
    var filterDropdown = document.getElementById('filterDropdown');

    filterButton.addEventListener('click', function() {
        filterDropdown.classList.toggle('hidden');
    });

    // Close the dropdown if clicked outside
    document.addEventListener('click', function(event) {
        var clickedInside = filterButton.contains(event.target) || filterDropdown.contains(event.target);
        if (!clickedInside) {
            filterDropdown.classList.add('hidden');
        }
    });
}
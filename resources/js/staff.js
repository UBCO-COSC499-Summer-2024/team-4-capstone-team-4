window.onload = function() {
    // select all function
    document.getElementById('staff-select-all').addEventListener('change', function(event) {
        var checkboxes = document.querySelectorAll('.staff-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = event.target.checked;
        });
    });

    //filter dropdown
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

    // add target hours 
    document.getElementById('add-target-hours').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('target-hours-modal').classList.remove('hidden');
    });

    //close add target hours modal
    document.getElementById('close-modal').addEventListener('click', function(event) {
        document.getElementById('target-hours-modal').classList.add('hidden');
    });
}
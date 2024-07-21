document.addEventListener('DOMContentLoaded', function () {
    function initializeSelectAll() {
        const selectAllCheckbox = document.getElementById('staff-select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.staff-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                    // Dispatch a custom event to Livewire
                    checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });
        }
    }

    // Ensure the function runs when the page loads
    initializeSelectAll();

    // Re-run the function when Livewire is loaded
    document.addEventListener('livewire:load', initializeSelectAll);
    document.addEventListener('livewire:update', initializeSelectAll);
    //filter dropdown
    var filterButton = document.getElementById('filterButton');
    var filterDropdown = document.getElementById('filterDropdown');
    if (filterButton && filterDropdown) {
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

});
document.addEventListener('DOMContentLoaded', function () {

    initializeSelectAll();

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
document.addEventListener('livewire:init', initializeSelectAll);
document.addEventListener('livewire:load', initializeSelectAll);
document.addEventListener('livewire:update', initializeSelectAll);

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
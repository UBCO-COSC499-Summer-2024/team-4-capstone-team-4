window.onload = function() {
    document.getElementById('staff-select-all').addEventListener('change', function(event) {
        var checkboxes = document.querySelectorAll('.staff-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = event.target.checked;
        });
    });
}
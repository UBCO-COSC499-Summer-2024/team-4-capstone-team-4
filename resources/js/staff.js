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
    var add_target_hours =  document.getElementById('add-target-hours');
    if(add_target_hours ){
        add_target_hours.addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('target-hours-modal').classList.remove('hidden');
        });
    }

    //close add target hours modal
    var close_modal =  document.getElementById('close-modal');
    if(close_modal){
        close_modal.addEventListener('click', function(event) {
            document.getElementById('target-hours-modal').classList.add('hidden');
        }); 
    }
  
    //find all changed inputs in edit mode
    var changedInputs = [];
    var emails = [];

    document.querySelectorAll('input[name="hours"]').forEach(input => {
        input.addEventListener('input', function() {
            var originalValue = this.getAttribute('data-original-value');
            if (this.value !== originalValue) {
                changedInputs.push(this.value);
                emails.push(getEmail(this));
            }
        });
    });

    document.getElementById('staff-save').addEventListener('click', function(event){
        event.preventDefault();

        $.ajax({
            url: '/staff-edit-mode',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                emails: emails,
                changedInputs: changedInputs
            },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
}

function getEmail(input) {
    var row = input.closest('tr');
    // Find the email within this row
    var email = row.querySelector('p[name="email"]');
    return email ? email.value : null;
}
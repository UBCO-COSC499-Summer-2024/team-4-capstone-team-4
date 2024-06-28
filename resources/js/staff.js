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
   /*  var add_target_hours =  document.getElementById('add-target-hours');
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
    } */
  
    //find all changed inputs in edit mode
  /*   var changedInputs = [];
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

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') } 
        });

        $.ajax({
            url: "/staff",
            type: "PATCH",
            dataType: "JSON", 
            data: {
                info: JSON.stringify(emails)
            },
            contentType: 'application/json; charset=utf-8',
            cache: false,
            success: function(data) {
                console.log(data);
                console.log("success");
            },
            error: function(data) {
                console.log(data);
                console.log("error");
            }
        });
    }); */
}

function getEmail(input) {
    var row = input.closest('tr');
    // Find the email within this row
    var email = row.querySelector('p[name="email"]');
    return email ? email.value : null;
} 

/* document.addEventListener('DOMContentLoaded', function () {
    const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

    const comparer = (idx, asc) => (a, b) => ((v1, v2) =>
        v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
    )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

    document.querySelectorAll('th.sortable').forEach(th => th.addEventListener('click', (() => {
        const table = th.closest('table');
        Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
            .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
            .forEach(tr => table.appendChild(tr));
        
        // Update sort direction class
        th.classList.toggle('th-sort-asc', this.asc);
        th.classList.toggle('th-sort-desc', !this.asc);

        // Remove sort direction class from other headers
        Array.from(th.parentNode.children).forEach(header => {
            if (header !== th) {
                header.classList.remove('th-sort-asc', 'th-sort-desc');
            }
        });
    })));
}); */
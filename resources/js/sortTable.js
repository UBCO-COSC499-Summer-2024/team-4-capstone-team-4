document.addEventListener('DOMContentLoaded', function () {
    const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

    const comparer = (idx, asc) => (a, b) => ((v1, v2) => 
        v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
    )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

    document.querySelectorAll('.sort-icon').forEach(icon => icon.addEventListener('click', function () {
        const th = icon.closest('th');
        const table = th.closest('table');
        const tbody = table.querySelector('tbody');
        const direction = icon.getAttribute('data-direction') === 'asc';
        const newDirection = direction ? 'desc' : 'asc';

        // Update data-direction attribute for the clicked icon
        icon.setAttribute('data-direction', newDirection);

        // Perform the sort
        Array.from(tbody.querySelectorAll('tr'))
            .sort(comparer(Array.from(th.parentNode.children).indexOf(th), direction))
            .forEach(tr => tbody.appendChild(tr));
        
        // Update the icon classes
        th.querySelectorAll('.sort-icon').forEach(i => {
            i.classList.remove('active');
            i.textContent = 'unfold_more';
        });
        icon.classList.add('active');

        // Toggle the icon's text based on the new direction
        icon.textContent = newDirection === 'asc' ? 'arrow_drop_up' : 'arrow_drop_down';
    }));
});

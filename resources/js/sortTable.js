document.addEventListener('DOMContentLoaded', function () {
    const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

    const comparer = (idx, asc) => (a, b) => ((v1, v2) => 
        v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
        )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

    document.querySelectorAll('.sort-icon').forEach(icon => icon.addEventListener('click', function () {
        const th = icon.closest('th');
        const table = th.closest('table');
        const tbody = table.querySelector('tbody');
        const field = icon.getAttribute('data-field');
        const direction = icon.getAttribute('data-direction') === 'asc';

        Array.from(tbody.querySelectorAll('tr'))
            .sort(comparer(Array.from(th.parentNode.children).indexOf(th), direction))
            .forEach(tr => tbody.appendChild(tr));
        
        th.querySelectorAll('.sort-icon').forEach(i => i.classList.remove('active'));
        icon.classList.add('active');
    }));
});
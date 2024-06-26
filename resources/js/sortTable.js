document.addEventListener('DOMContentLoaded', function () {
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
});
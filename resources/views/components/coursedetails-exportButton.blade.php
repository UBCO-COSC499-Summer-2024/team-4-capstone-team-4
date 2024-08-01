<div class="relative inline-block text-left">
    <button id="exportButton" type="button" class="custom-ubc-blue hover:text-white focus:ring-1 focus:outline-none font-bold rounded-lg text-sm px-5 py-2 text-center me-1 mb-2" aria-expanded="true" aria-haspopup="true">
        <span class="material-symbols-outlined">ios_share</span>
    </button>
    <div id="dropdownMenu" class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="exportButton">
        <div class="py-1" role="none">
            <a href="#" id="exportPDF" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem">Export as PDF</a>
            <a href="#" id="exportCSV" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem">Export as CSV</a>
        </div>
    </div>
</div>


<style>
    .tooltip {
        display: block;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    #exportButton:hover .tooltip {
        opacity: 1;
    }
</style>

<script>document.addEventListener('DOMContentLoaded', function () {
    const exportButton = document.getElementById('exportButton');
    const dropdownMenu = document.getElementById('dropdownMenu');

    exportButton.addEventListener('click', function () {
        dropdownMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function (event) {
        if (!exportButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });

    document.getElementById('exportPdf').addEventListener('click', function() {
    fetch('/export/pdf')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to save PDF. Please try again.');
            }
            return response.blob();
        })
        .then(blob => {
            const url = window.URL.createObjectURL(new Blob([blob]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'course_sections.pdf');
            document.body.appendChild(link);
            link.click();
            link.parentNode.removeChild(link);
        })
        .catch(error => {
            alert(error.message);
        });
});


    document.getElementById('exportCSV').addEventListener('click', function () {
        exportTable('csv');
    });

    function exportTable(format) {
        let url = '';
        if (format === 'pdf') {
            url = "{{ route('export.pdf') }}";
        } else if (format === 'csv') {
            url = "{{ route('export.csv') }}";
        }
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to save file.');
                }
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = `table.${format}`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(error => {
                alert(error.message);
            });
    }
});
</script>

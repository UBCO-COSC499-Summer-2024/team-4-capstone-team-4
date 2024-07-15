document.addEventListener('DOMContentLoaded', function() {
    var button = document.getElementById('exportButton');

    button.addEventListener('click', function(event) {
        event.preventDefault();

        var { jsPDF } = window.jspdf;
        var pdf = new jsPDF('l', 'pt', 'letter'); 

        pdf.setFont('helvetica', 'bold'); // Make font bold
        pdf.setFontSize(18);
        var titleText = document.querySelector('.content-title-text').textContent;
        var year = document.getElementById('year').value;
        pdf.text(titleText + ' - ' + year, 40, 30);

        pdf.setFont('helvetica', 'normal'); // Reset font to normal
        pdf.setFontSize(12);
        pdf.text('Courses Performance', 40, 50);

        var courseTable = document.getElementById('coursesTable');
        var perfTable = document.getElementById('performanceTable');
        var areaCourseTables = document.querySelectorAll('.areaCourseTable');
        var areaPerfTables = document.querySelectorAll('.areaPerfTable');

        // Check if the courses table exists
        if (courseTable) {
            pdf.autoTable({ 
                html: '#coursesTable', 
                startY: 60, 
                useCss: true, 
            });
        } else {
            console.error('Courses table not found.');
        }

        // Move to next section for area-specific course tables
        pdf.text('Area-Specific Course Performance', 40, pdf.lastAutoTable.finalY + 30);

        // Check if the area-specific course tables exist
        if (areaCourseTables.length > 0) {
            areaCourseTables.forEach(table => {
                pdf.autoTable({ 
                    html: table, 
                    startY: pdf.lastAutoTable.finalY + 40, 
                    useCss: true,
                    tableWidth: 'auto',
                });
            });
        } else {
            console.error('Area-specific course tables not found.');
        }

        // Move to next section for performance tables
        pdf.text('Service Roles & Extra Hours Performance', 40, pdf.lastAutoTable.finalY + 30);

        // Check if the performance table exists
        if (perfTable) {
            pdf.autoTable({ 
                html: '#performanceTable', 
                startY: pdf.lastAutoTable.finalY + 40, 
                useCss: true,
                tableWidth: 'auto',
            });
        } else {
            console.error('Performance table not found.');
        }

        // Move to next section for area-specific performance tables
        pdf.text('Area-Specific Performance', 40, pdf.lastAutoTable.finalY + 30);

        // Check if the area-specific performance tables exist
        if (areaPerfTables.length > 0) {
            areaPerfTables.forEach(table => {
                pdf.autoTable({ 
                    html: table, 
                    startY: pdf.lastAutoTable.finalY + 40, 
                    useCss: true,
                    tableWidth: 'auto',
                });
            });
        } else {
            console.error('Area-specific performance tables not found.');
        }

        // Save PDF
        pdf.save(titleText + '-' + year + '.pdf');
    });
});

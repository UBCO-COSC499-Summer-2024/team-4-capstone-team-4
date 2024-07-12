document.addEventListener('DOMContentLoaded', function() {
    var button = document.getElementById('exportButton');

    button.addEventListener('click', function(event) {
        event.preventDefault();

        var { jsPDF } = window.jspdf;
        var pdf = new jsPDF('l', 'pt', 'letter'); 

        pdf.setFont('helvetica', 'bold'); //make font bold
        pdf.setFontSize(18)
        var titleText = document.querySelector('.content-title-text').textContent;
        var year = document.getElementById('year').value;
        pdf.text(titleText + ' - ' + year, 40, 30);

        pdf.setFont('helvetica', 'normal'); //reset font to normal
        pdf.setFontSize(12)
        pdf.text('Courses Performance', 40, 50);

        var courseTable = document.getElementById('courseTable');
        var perfTable = document.getElementById('performanceTable');

        // Check if the courses table exists
        if (courseTable) {
            pdf.autoTable({ 
                html: '#courseTable', 
                startY: 60, 
                useCss: true, 
            });
        } else {
            console.error('Courses table not found.');
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
                //styles: { fontSize: 0.5 },
            });
        } else {
            console.error('Performance table not found.');
        }
        //save pdf
        pdf.save(titleText + '-' + year + '.pdf');
    });
});

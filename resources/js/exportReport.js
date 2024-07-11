document.addEventListener('DOMContentLoaded', function() {
    var button = document.getElementById('exportButton');

    button.addEventListener('click', function(event) {
        event.preventDefault();

        var { jsPDF } = window.jspdf;
        var pdf = new jsPDF('p', 'pt', 'letter'); 

        pdf.text('Courses Performance', 40, 30);

        var courseTable = document.getElementById('courseTable');
        var perfTable = document.getElementById('performanceTable');

        // Check if the courses table exists
        if (courseTable) {
            pdf.autoTable({ 
                html: '#courseTable', 
                startY: 40, 
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
            });
        } else {
            console.error('Performance table not found.');
        }
        //save pdf
        pdf.save('report.pdf');
    });
});

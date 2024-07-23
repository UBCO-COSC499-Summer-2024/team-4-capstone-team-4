document.addEventListener('DOMContentLoaded', function() {
    var button = document.getElementById('exportButton');

    if(button){
        button.addEventListener('click', function(event) {
            event.preventDefault();
    
            var { jsPDF } = window.jspdf;
            var pdf = new jsPDF('l', 'pt', 'letter'); 

            try {
                pdf.setFont('helvetica', 'bold'); // Make font bold
                pdf.setFontSize(18);
                var titleText = document.querySelector('.content-title-text').textContent;
                var year = document.getElementById('year').value;
                pdf.text(titleText + ' - ' + year, 40, 30);
        
                pdf.setFont('helvetica', 'normal'); // Reset font to normal
                pdf.setFontSize(12);
                pdf.text('Courses Performance', 40, 50);
        
                var courseTable = document.getElementById('courseTable');
                var perfTable = document.getElementById('performanceTable');
                var areaCourseTables = document.querySelectorAll('.areaCourseTable');
                var areaPerfTables = document.querySelectorAll('.areaPerfTable');
        
                // Check if the courses table exists
                if (courseTable) {
                    if (areaCourseTables.length > 0) {
                        pdf.text('Summary', 40, 70);
                        pdf.autoTable({ 
                            html: '#courseTable', 
                            startY: 80, 
                            useCss: true, 
                        });
                    }else{
                        pdf.autoTable({ 
                            html: '#courseTable', 
                            startY: 60, 
                            useCss: true, 
                        });
                    }  
                } else {
                    console.error('Courses table not found.');
                }
        
                // Check if the area-specific course tables exist
                if (areaCourseTables.length > 0) {
                    areaCourseTables.forEach(table => {
                        var header = table.previousElementSibling;
                        if (header && header.tagName === 'H3') {
                            pdf.text(header.textContent, 40, pdf.lastAutoTable.finalY + 30);
                        }
                        pdf.autoTable({ 
                            html: table, 
                            startY: pdf.lastAutoTable.finalY + 40, 
                            useCss: true,
                            tableWidth: 'auto',
                        });
                    });
                }
        
                // Move to next section for performance tables
                pdf.text('Service Roles & Extra Hours Performance', 40, pdf.lastAutoTable.finalY + 30);
        
                // Check if the performance table exists
                if (perfTable) {
                    if (areaPerfTables.length > 0) {
                        pdf.text('Summary', 40, pdf.lastAutoTable.finalY + 50);
                    }
                    pdf.autoTable({ 
                        html: '#performanceTable', 
                        startY: pdf.lastAutoTable.finalY + 40, 
                        useCss: true,
                        tableWidth: 'auto',
                    });
                } else {
                    console.error('Performance table not found.');
                }
        
                // Check if the area-specific performance tables exist
                if (areaPerfTables.length > 0) {
                    areaPerfTables.forEach(table => {
                        var header = table.previousElementSibling;
                        if (header && header.tagName === 'H3') {
                            pdf.text(header.textContent, 40, pdf.lastAutoTable.finalY + 30);
                        }
                        pdf.autoTable({ 
                            html: table, 
                            startY: pdf.lastAutoTable.finalY + 40, 
                            useCss: true,
                            tableWidth: 'auto',
                        });
                    });
                }
        
                // Save PDF
                pdf.save(titleText + '-' + year + '.pdf');
                // PDF saved successfully
                setTimeout(function() {
                    console.log("hello");
                    Livewire.dispatch('pdfSaved', { fileName: titleText + '-' + year + '.pdf' });
                }, 1000); 
                
            } catch (error) {
                console.error("An error occurred while saving the PDF:", error);
                alert("Failed to save PDF. Please try again.");
            }

        });
    }
});

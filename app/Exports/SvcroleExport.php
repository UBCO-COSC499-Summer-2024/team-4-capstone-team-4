<?php

namespace App\Exports;

use App\Models\ServiceRole;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Concerns\FromCollection;

class SvcroleExport implements FromCollection {
    protected $serviceRoles;

    public function __construct($serviceRoles)
    {
        $this->serviceRoles = $serviceRoles;
    }

    public function collection()
    {
        return $this->serviceRoles;
    }

    public function generatePDF()
    {
        $pdf = Pdf::loadView('exports.pdf.servicerole', ['serviceRoles' => $this->serviceRoles]);
        return $pdf->download('service_roles_report.pdf');
    }

    public function generateMultiplePDF() {
        $pdf = Pdf::loadView('exports.pdf.servicerole', ['serviceRoles' => $this->serviceRoles]);
        return $pdf->download('service_roles_report.pdf');
    }
}

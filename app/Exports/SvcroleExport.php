<?php

namespace App\Exports;

use App\Models\ServiceRole;
use Illuminate\Support\Str;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\Browsershot\Browsershot;
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
        $filename = 'Service_Role_' . Str::slug($this->serviceRoles->first()->name) . '.pdf';
        // $pdf = Pdf::loadView('exports.pdf.servicerole', ['serviceRoles' => $this->serviceRoles]);
        // return $pdf->download('service_roles_report.pdf');
        $bs = Browsershot::url(route('exports.pdf.preview', ['id' => $this->serviceRoles->first()->id]))
        // $bs = Browsershot::html(view('exports.pdf.servicerole', ['serviceRoles' => $this->serviceRoles, 'id' => $this->serviceRoles->first()->id])->render())
        ->format('A4')
        ->pdfQuality(100)
        ->margins(0.1, 0.1, 0.1, 0.1)
        ->showBackground()
        ->displayHeaderFooter()
        ->timeout(60000)
        ->waitUntilNetworkIdle();
        return $bs->save($filename);
    }

    public function generateMultiplePDF() {
        $pdf = Pdf::view('exports.pdf.servicerole', ['serviceRoles' => $this->serviceRoles]);
        return $pdf->download('service_roles_report.pdf');
    }
}

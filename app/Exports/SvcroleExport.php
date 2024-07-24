<?php

namespace App\Exports;

use App\Models\ServiceRole;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
// use Spatie\LaravelPdf\Facades\Pdf;
// use Spatie\Browsershot\Browsershot;
use Maatwebsite\Excel\Concerns\FromCollection;

use function Spatie\LaravelPdf\Support\pdf;

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

    private function getRenderedView()
    {
        return view('exports.pdf.servicerole', ['serviceRoles' => $this->serviceRoles, 'id' => $this->serviceRoles->first()->id])->render();
    }

    public function generatePDF()
    {
        $filename = 'Service_Role_' . Str::slug($this->serviceRoles->first()->name) . '.pdf';
        try {
            // $pdf = Pdf::loadHtml($this->getRenderedView())
            // $pdf = Pdf::loadView('exports.pdf.servicerole', ['serviceRoles' => $this->serviceRoles, 'id' => $this->serviceRoles->first()->id])
            // ->setOptions([
            //     'isPhpEnabled' => true,
            //     // 'isHtml5ParserEnabled' => true,
            //     'isRemoteEnabled' => true,
            //     'isJavascriptEnabled' => true
            // ])
            $pdf = pdf()->view('exports.pdf.servicerole', ['serviceRoles' => $this->serviceRoles, 'id' => $this->serviceRoles->first()->id])
            // change executable
            ->withBrowsershot(function ($browsershot) {
                $browsershot->setNodeBinary('/usr/bin/node')
                ->setNpmBinary('/usr/bin/npm')
                ->timeout(300)
                ->setChromePath('/usr/bin/google-chrome')
                ->waitUntilNetworkIdle()
                ->setOption('debug', true)
                ->setOption('verbose', true);
                // dd($browsershot);
            })
            ->format('A4')
            ->margins(10, 10, 10, 10)
            ->portrait()
            ->download($filename);
            return $pdf;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function generateMultiplePDF() {
        $pdf = Pdf::view('exports.pdf.servicerole', ['serviceRoles' => $this->serviceRoles]);
        return $pdf->download('service_roles_report.pdf');
    }
}

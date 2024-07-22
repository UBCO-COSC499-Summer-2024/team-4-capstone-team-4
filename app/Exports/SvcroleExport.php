<?php

namespace App\Exports;

use App\Models\ServiceRole;
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
}

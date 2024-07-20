<?php

namespace App\Exports;

use App\Models\ServiceRole;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SvcroleExport implements FromCollection, WithHeadings, WithMapping {
    protected $serviceRoles;

    public function __construct($serviceRoles)
    {
        $this->serviceRoles = $serviceRoles;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->serviceRoles;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Year',
            'Monthly Hours',
            'Area ID',
            'Created At',
            'Updated At',
        ];
    }

    public function map($serviceRole): array
    {
        return [
            $serviceRole->id,
            $serviceRole->name,
            $serviceRole->description,
            $serviceRole->year,
            $serviceRole->monthly_hours,
            $serviceRole->area_id,
            $serviceRole->created_at,
            $serviceRole->updated_at,
        ];
    }
}

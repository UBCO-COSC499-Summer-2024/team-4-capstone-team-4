<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ServiceRole;
use App\Models\Area;

class ServiceRoleForm extends Component
{
    public $name = '';
    public $description = '';
    public $year = 2024;
    public $monthly_hours = [
        'January' => 0,
        'February' => 0,
        'March' => 0,
        'April' => 0,
        'May' => 0,
        'June' => 0,
        'July' => 0,
        'August' => 0,
        'September' => 0,
        'October' => 0,
        'November' => 0,
        'December' => 0
    ];
    public $area_id;
    public $areas;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'year' => 'required|integer',
        'monthly_hours' => 'required|array',
        'area_id' => 'required|exists:areas,id',
    ];

    public function mount()
    {
        $this->areas = Area::all();
        $this->initializeMonthlyHours();
    }

    public function save()
    {
        $this->validate();

        $serviceRole = ServiceRole::create([
            'name' => $this->name,
            'description' => $this->description,
            'year' => $this->year,
            'monthly_hours' => $this->monthly_hours,
            'area_id' => $this->area_id,
        ]);

        $this->toast('Service Role created successfully.');

        $this->resetForm();
    }

    public function toast($message)
    {
        $this->dispatch('show-toast', ['message' => $message, 'type' => 'success']);
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->year = '';
        $this->initializeMonthlyHours();
        $this->area_id = '';
    }

    private function initializeMonthlyHours()
    {
        $this->monthly_hours = [
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0,
            'August' => 0,
            'September' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0,
        ];
    }

    public function render()
    {
        return view('livewire.service-role-form');
    }
}

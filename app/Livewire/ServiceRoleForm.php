<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ServiceRole;
use App\Models\Area;
use App\Models\AuditLog;
use App\Models\User;

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
    public $stay = true;
    public $room;
    public $roomB;
    public $roomN;
    public $roomS;
    public $audit_user;
    public $monthly_hrs_by_year_cache = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'year' => 'required|integer',
        'monthly_hours' => 'required|array',
        'area_id' => 'required|exists:areas,id',
        'room' => 'nullable|string|max:255',
    ];

    public function mount() {
        $this->areas = Area::all();
        $this->initializeMonthlyHours();
        $this->audit_user = User::find((int) auth()->user()->id)->getName();
    }

    public function concatRoom() {
        $this->room = $this->roomB . ($this->roomN ? ' ' . $this->roomN : '') . ($this->roomS ? ' ' . $this->roomS : '');
        $this->room = trim($this->room);
    }

    public function updatedYear($value) {
        $this->year = (int) $value;
        $this->monthly_hours = $this->getMonthlyHoursByYear($this->year);
    }

    public function incrementYear() {
        $this->updatedMonthlyHours($this->year);
        $this->year++;
        $this->monthly_hours = $this->getMonthlyHoursByYear($this->year);
    }

    public function decrementYear() {
        $this->updatedMonthlyHours($this->year);
        $this->year--;
        $this->monthly_hours = $this->getMonthlyHoursByYear($this->year);
    }

    // when monthly_hours is updated, update the cache
    public function updatedMonthlyHours($year) {
        $this->monthly_hrs_by_year_cache[$year] = $this->monthly_hours;
    }

    public function save() {
        try {
            $this->concatRoom();
            $this->validate();

            $serviceRole = ServiceRole::where('name', $this->name)
                ->where('year', $this->year)
                ->where('area_id', $this->area_id)
                ->first();

            if ($serviceRole) {
                $this->toast('Service Role already exists.', 'error');
                return;
            }

            $serviceRole = ServiceRole::create([
                'name' => $this->name,
                'description' => $this->description,
                'year' => $this->year,
                'monthly_hours' => $this->monthly_hours,
                'area_id' => $this->area_id,
                'room' => $this->room,
            ]);

            $this->toast('Service Role created successfully.', 'success', [
                'destination' => route('svcroles.manage.id', ['id' => $serviceRole->id]),
            ]);

            $this->resetForm();
            ServiceRole::audit('create', [
                'operation_type' => 'CREATE',
                'new_value' => json_encode($serviceRole->getAttributes()),
            ], $this->audit_user . ' created a new Service Role: ' . $serviceRole->name);

            $this->monthly_hrs_by_year_cache = [];

            if (!$this->stay) {
                $url = route('svcroles.manage.id', ['id' => $serviceRole->id]);
                return redirect($url);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->toast('An error occurred while creating the Service Role.', 'error');
            ServiceRole::audit('create error', [
                'operation_type' => 'CREATE',
            ], $this->audit_user . ' tried to create a new Service Role but an error occurred. \n' . $e->getMessage());
        }
    }

    public function toast($message, $type, $options = []) {
        $this->dispatch('show-toast', [
            'message' => $message,
            'type' => $type,
            ...$options,
        ]);
    }

    public function resetForm() {
        $this->name = '';
        $this->description = '';
        $this->year = date('Y');
        $this->initializeMonthlyHours();
        $this->area_id = '';
        $this->room = '';
        $this->roomB = '';
        $this->roomN = '';
        $this->roomS = '';
    }

    private function initializeMonthlyHours() {
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

    public function getMonthlyHoursByYear($year) {
        return $this->monthly_hrs_by_year_cache[$year] ?? [
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

    public function render() {
        return view('livewire.service-role-form');
    }
}

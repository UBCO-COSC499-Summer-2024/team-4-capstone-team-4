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
    public $stay;

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
        $audit_user = User::find((int) auth()->user()->id)->getName();

        try {
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
            ]);

            $this->toast('Service Role created successfully.', 'success', [
                'destination' => route('svcroles.manage.id', ['id' => $serviceRole->id]),
            ]);

            $this->resetForm();

            AuditLog::create([
                'user_id' => (int) auth()->user()->id,
                'user_alt' => $audit_user,
                'action' => 'create',
                'table_name' => 'service_roles',
                'operation_type' => 'CREATE',
                'new_value' => json_encode($serviceRole),
                'description' => $audit_user . ' created a new Service Role: ' . $serviceRole->name,
            ]);

            if (!$this->stay) {
                $url = route('svcroles.manage.id', ['id' => $serviceRole->id]);
                return redirect($url);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation exceptions are handled automatically and sent to the front end.
            throw $e;

        } catch (\Exception $e) {
            $this->toast('An error occurred while creating the Service Role.', 'error');
            AuditLog::create([
                'user_id' => (int) auth()->user()->id,
                'user_alt' => $audit_user,
                'action' => 'create',
                'table_name' => 'service_roles',
                'operation_type' => 'CREATE',
                'description' => $audit_user . ' tried to create a new Service Role but an error occurred. \n' . $e->getMessage(),
            ]);
        }
    }

    public function toast($message, $type, $options = [])
    {
        $this->dispatch('show-toast', [
            'message' => $message,
            'type' => $type,
            ...$options,
        ]);
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

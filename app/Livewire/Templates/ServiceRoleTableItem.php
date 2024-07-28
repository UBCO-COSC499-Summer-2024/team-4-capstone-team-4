<?php

namespace App\Livewire\Templates;

use App\Models\Area;
use App\Models\AuditLog;
use App\Models\ServiceRole;
use App\Models\User;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class ServiceRoleTableItem extends Component
{
    public $svcrole;
    public $areas;
    public $requires_update = false;
    public $original_area_name = '';
    public $isSaved = false;
    public $id;
    public $monthly_hrs = [
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

    protected $rules = [
        'svcrole.name' => 'required|string|max:255',
        'svcrole.description' => 'required|string|max:255',
        // validate area, id
        'svcrole.area_id' => 'required|integer|exists:areas,id',
        'svcrole.monthly_hours.*' => 'required|integer|min:0|max:200',
        'svcrole.year' => 'required|integer|min:2021|max:2030',
    ];

    protected $listeners = [
        'svcr-add-save-svcroles' => 'storeSvcrole',
    ];

    public function mount($svcrole) {
        // dd($svcrole);
        $this->svcrole = $svcrole;
        $this->areas = Area::all();
        $this->requires_update = $svcrole['updateMe'];
        $this->original_area_name = $svcrole['original_area_name'];
        $this->id = $svcrole['id'];
        //  delete the original area name from the svcrole object as well as the updateMe flag
        unset($this->svcrole['original_area_name']);
        unset($this->svcrole['updateMe']);
        unset($this->svcrole['id']);
        $this->monthly_hrs = json_decode($svcrole['monthly_hours'], true);
    }

    public function render() {
        return view('livewire.templates.service-role-table-item', [
            'svcrole' => $this->svcrole,
            'areas' => $this->areas,
            'requires_update' => $this->requires_update,
        ]);
    }

    public function deleteItem($id) {
        $this->dispatch('svcr-add-table-delete-item', $id);
    }

    public function storeSvcrole($svcroles) {
        // dd($svcroles);
        $isFound = false;
        foreach ($svcroles as $index => $svcrole) {
            if ($svcrole['id'] == $this->id) {
                $isFound = true;
                break;
            }
        }
        if (!$isFound) {
            return;
        }
        $audit_user = User::find(auth()->id())->getName();
        $operation = $this->requires_update ? 'UPDATE' : 'CREATE';
        $oldValue = null;
        $svcrole['monthly_hours'] = json_encode($this->monthly_hrs);
        try {
            $this->validate();
            if ($this->requires_update) {
                $existingServiceRole = ServiceRole::find('name', $this->svcrole->name)->where('area_id', $this->svcrole->area_id)->where('year', $this->svcrole->year)->first();
                $oldValue = $existingServiceRole->getOriginal();
                $existingServiceRole->update([
                    'name' => $this->svcrole['name'],
                    'description' => $this->svcrole['description'],
                    'area_id' => $this->svcrole['area_id'],
                    'monthly_hours' => $this->svcrole['monthly_hours'],
                    'year' => $this->svcrole['year'],
                ]);
            } else {
                ServiceRole::create([
                    'name' => $this->svcrole['name'],
                    'description' => $this->svcrole['description'],
                    'area_id' => $this->svcrole['area_id'],
                    'monthly_hours' => $this->svcrole['monthly_hours'],
                    'year' => $this->svcrole['year'],
                ]);
            }
            $this->isSaved = true;
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Imported Service Role has been saved successfully.',
            ]);
            AuditLog::create([
                'user_id' => auth()->id(),
                'user_alt' => $audit_user,
                'table_name' => 'service_roles',
                'old_value' => json_encode($oldValue),
                'new_value' => json_encode($this->svcrole),
                'operation_type' => $operation,
                'action' => 'Success',
                'description' => 'Imported Service Role has been saved successfully.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->isSaved = false;
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'An error occurred while saving the service role.'. $e->getMessage(),
            ]);
            AuditLog::create([
                'user_id' => auth()->id(),
                'user_alt' => $audit_user,
                'operation_type' => 'CREATE',
                'table_name' => 'service_roles',
                'action' => 'Error',
                'description' => 'An error occurred while saving the service role.',
            ]);
        }
    }
}

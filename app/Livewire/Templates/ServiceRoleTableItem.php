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
    public $name;
    public $room; // concatenated room string (LIB 101 A)
    public $roomB; // room building short code (LIB)
    public $roomN; // room number (101)
    public $roomS; // room suffix (A)
    public $description;
    public $area_id;
    public $year;
    public $archived;
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
    public $audit_user;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'area_id' => 'required|integer|exists:areas,id',
        'monthly_hrs.*' => 'required|integer|min:0|max:200',
        'year' => 'required|integer|min:2021|max:2030',
        'archived' => 'required|boolean',
        'room' => 'nullable|string|max:255',
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
        $this->name = $svcrole['name'];
        $this->description = $svcrole['description'];
        $this->area_id = $svcrole['area_id'];
        $this->year = $svcrole['year'];
        $this->room = $svcrole['room'] ?? null;
        $this->getRoomDetails($this->room);
        $this->archived = $svcrole['archived'];
        //  delete the original area name from the svcrole object as well as the updateMe flag
        unset($this->svcrole['original_area_name']);
        unset($this->svcrole['updateMe']);
        unset($this->svcrole['id']);
        $this->monthly_hrs = json_decode($svcrole['monthly_hours'], true);
        $this->audit_user = User::find(auth()->id())->getName();
    }

    public function getRoomDetails($room) {
        $building = null;
        $room_number = null;
        $suffix = null;
        if ($room) {
            // explode either by space or hyphen or underscore
            $parts = preg_split('/[\s-_]/', $room);
            $building = $parts[0];
            $room_number = $parts[1];
            $suffix = $parts[2] ?? null;
        }
        $this->roomB = $building;
        $this->roomN = $room_number;
        $this->roomS = $suffix;
    }

    public function render() {
        return view('livewire.templates.service-role-table-item', [
            'svcrole' => $this->svcrole,
            'areas' => $this->areas,
            'requires_update' => $this->requires_update,
        ]);
    }

    public function concatRoom() {
        // $this->room = $this->roomB . ' ' . $this->roomN . ($this->roomS ? ' ' . $this->roomS : '');
        // if not null
        $this->room = $this->roomB . ($this->roomN ? ' ' . $this->roomN : '') . ($this->roomS ? ' ' . $this->roomS : '');
        $this->room = trim($this->room);
    }

    public function deleteItem($id) {
        $this->dispatch('svcr-add-table-delete-item', $id);
    }

    public function storeSvcrole($svcroles) {
        $isFound = false;
        $operation = $this->requires_update ? 'UPDATE' : 'CREATE';
        foreach ($svcroles as $index => $svcrole) {
            if ($svcrole['id'] == $this->id) {
                $isFound = true;
                break;
            }
        }
        if (!$isFound) {
            return;
        }
        $oldValue = null;
        $this->svcrole['monthly_hours'] = json_encode($this->monthly_hrs);
        $this->svcrole['name'] = $this->name;
        $this->svcrole['description'] = $this->description;
        $this->svcrole['area_id'] = $this->area_id;
        $this->svcrole['year'] = $this->year;
        $this->svcrole['archived'] = $this->archived;
        $this->concatRoom();
        $this->svcrole['room'] = $this->room;
        try {
            $this->validate(); // Validate before any database operations

            if ($this->requires_update) {
                $existingServiceRole = ServiceRole::where('name', $this->name)
                    ->where('area_id', $this->area_id)
                    ->where('year', $this->year)
                    ->first();
                $oldValue = $existingServiceRole->getOriginal();
                $existingServiceRole->update([
                    'name' => $this->svcrole['name'],
                    'description' => $this->svcrole['description'],
                    'area_id' => $this->svcrole['area_id'],
                    'monthly_hours' => $this->svcrole['monthly_hours'],
                    'year' => $this->svcrole['year'],
                    'archived' => $this->svcrole['archived'],
                    'room' => $this->svcrole['room'],
                ]);
            } else {
                ServiceRole::create([
                    'name' => $this->svcrole['name'],
                    'description' => $this->svcrole['description'],
                    'area_id' => $this->svcrole['area_id'],
                    'monthly_hours' => $this->svcrole['monthly_hours'],
                    'year' => $this->svcrole['year'],
                    'archived' => $this->svcrole['archived'],
                    'room' => $this->svcrole['room'],
                ]);
            }
            $this->isSaved = true;
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Imported Service Role has been saved successfully.',
            ]);
            ServiceRole::audit('import', [
                'operation_type' => $operation,
                'old_value' => json_encode($oldValue),
                'new_value' => json_encode($this->svcrole),
            ], $this->audit_user . ' imported a service role: ' . $this->svcrole['name'] . ' successfully.');
            $this->svcrole['id'] = $this->id;
            $this->dispatch('svcr-add-table-item-updated', $this->svcrole);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->isSaved = false;
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'An error occurred while saving the service role.'. $e->getMessage(),
            ]);
            ServiceRole::audit('import error', [
                'operation_type' => $operation,
            ], $this->audit_user . ' encountered an error while importing a service role: ' . $this->svcrole['name'] . '. Error: ' . $e->getMessage());
        }
    }
}

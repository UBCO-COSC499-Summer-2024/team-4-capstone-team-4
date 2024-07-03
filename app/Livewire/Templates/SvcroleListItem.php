<?php

namespace App\Livewire\Templates;

use App\Models\Area;
use App\Models\ServiceRole;
use App\Models\UserRole;
use Livewire\Component;
use Illuminate\View\ComponentAttributeBag;

use function Laravel\Prompts\confirm;

class SvcroleListItem extends Component
{
    public $serviceRole;
    public $isEditing = false;
    public $isSelected = false;
    public $formattedAreas;

    // You might need to pass areas from the parent component
    // or fetch them in this component's mount() method
    public $areas;
    public $instructors;

    protected $listeners = [
        'toggleEditMode' => 'toggleEditMode',
        'editServiceRole' => 'editServiceRole',
        'updateServiceRole' => 'saveServiceRole',
        'deleteServiceRole' => 'deleteServiceRole'
    ];

    public function mount()
    {
        $this->areas = Area::all();
        $this->instructors = UserRole::where('role', 'instructor')->get();
        $this->formattedAreas = $this->getFormattedAreasProperty();
    }

    public function toggleEditMode($selectedItems)
    {
        if (in_array($this->serviceRole->id, $selectedItems)) {
            $this->isEditing = !$this->isEditing;
        }
    }

    public function deleteServiceRole($serviceRoleId)
    {
        $count = ServiceRole::destroy($serviceRoleId);
        if ($count > 0) {
            $this->dispatch('show-toast', [
                'message' => 'Service Role deleted successfully.',
                'type' => 'success'
            ]);
            $this->serviceRole = null;
        } else {
            $this->dispatch('show-toast', [
                'message' => 'Failed to delete Service Role.',
                'type' => 'error'
            ]);
        }
    }

    public function editServiceRole()
    {
        $this->isEditing = true;
    }

    public function saveServiceRole()
    {
        $this->validate([
            'serviceRole.name' => 'required',
            'serviceRole.year' => 'required|integer',
            'serviceRole.description' => 'nullable',
            'serviceRole.area_id' => 'required|exists:areas,id'
        ]);

        $this->serviceRole->save();
        $this->isEditing = false;

        // Optional: emit an event to the parent
        $this->dispatch('show-toast', [
            'message' => 'Service Role updated successfully.',
            'type' => 'success'
        ]);
    }

    public function confirmDelete($serviceRoleId)
    {
        $this->dispatch('confirmDelete', [
            'message' => 'Are you sure you want to delete this service role?',
            'serviceRoleId' => $serviceRoleId
        ]);
    }

    public function getFormattedAreasProperty()
    {
        return $this->areas->mapWithKeys(function ($area) {
            return [$area->id => $area->name];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.templates.svcrole-list-item', [
            'areas' => $this->areas, // Pass areas data to the view
        ]);
    }

    public function openExtraHourModal($serviceRoleId) {
        $this->dispatch('openModal', 'extra-hour-form', ['serviceRoleId' => $serviceRoleId]);
    }

    public function openExtraHourView($serviceRoleId) {
        $this->dispatch('openModal', 'extra-hour-view', ['serviceRoleId' => $serviceRoleId]);
    }
}

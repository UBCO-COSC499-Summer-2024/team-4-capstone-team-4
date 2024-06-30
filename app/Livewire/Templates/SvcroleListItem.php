<?php

namespace App\Livewire\Templates;

use App\Models\Area;
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

    protected $listeners = [
        'toggleEditMode' => 'toggleEditMode',
        'editServiceRole' => 'editServiceRole',
        'updateServiceRole' => 'saveServiceRole'
    ];

    public function mount()
    {
        $this->areas = Area::all();
        $this->formattedAreas = $this->getFormattedAreasProperty();
    }

    public function toggleEditMode($selectedItems)
    {
        if (in_array($this->serviceRole->id, $selectedItems)) {
            $this->isEditing = !$this->isEditing;
        }
    }

    public function editServiceRole()
    {
        $this->isEditing = true;
    }

    public function saveServiceRole()
    {
        // $this->validate([
        //     // Add your validation rules here
        //     'serviceRole.name' => 'required',
        //     'serviceRole.area_id' => 'required|exists:areas,id',
        //     'serviceRole.description' => 'nullable',
        //     // ... add validation for instructors if applicable
        // ]);

        $this->serviceRole->save();
        $this->isEditing = false;

        // Optional: emit an event to the parent
        $this->dispatch('serviceRoleUpdated');
    }

    public function confirmDelete($serviceRoleId)
    {
        if (confirm('Are you sure you want to delete this service role?')) {
            $this->serviceRole->delete();
            $this->dispatch('deleteServiceRole', $serviceRoleId);
        }
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
}

<?php

namespace App\Livewire\Templates;

use App\Models\Area;
use Livewire\Component;

use function Laravel\Prompts\confirm;

class SvcroleCardItem extends Component
{
    public $serviceRole;
    public $isEditing = false;
    public $areas; // For the area dropdown
    public $formattedAreas; // For the area dropdown
    protected $listeners = [
        'toggleEditMode' => 'toggleEditMode',
        'editServiceRole' => 'editServiceRole',
        'updateServiceRole' => 'saveServiceRole'
    ];

    public function mount() {
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
        //     'serviceRole.area_id' => 'required|exists:areas,id', // Ensure valid area
        //     'serviceRole.description' => 'nullable', // Or any rules you nee
        // ]);

        $this->serviceRole->save();
        $this->isEditing = false;

        // Optionally emit an event to the parent
        $this->dispatch('serviceRoleUpdated');
    }

    public function confirmDelete()
    {
        // Handle confirmation logic (e.g., using a modal)
        if (confirm('Are you sure you want to delete this service role?')) {
            $this->serviceRole->delete();
            $this->dispatch('serviceRoleDeleted'); // Optional: emit event to parent
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
        return view('livewire.templates.svcrole-card-item');
    }
}

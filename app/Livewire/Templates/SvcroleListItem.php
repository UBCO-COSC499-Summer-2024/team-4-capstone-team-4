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
        'toggle-edit-mode' => 'toggleEditMode',
        'editServiceRole' => 'editServiceRole',
        'updateServiceRole' => 'saveServiceRole',
        'svcr-item-delete' => 'deleteServiceRole',
        'item-modal-id' => 'updateModalId',
    ];

    public function mount()
    {
        $this->areas = Area::all();
        $this->instructors = UserRole::where('role', 'instructor')->get();
        $this->formattedAreas = $this->getFormattedAreasProperty();
    }

    public function toggleEditMode($data)
    {
        // selected items is in the format of [id => boolean, id => boolean, ...]
        // print type of selectedItems
        $selectedItems = $data['selectedItems'];
        foreach ($selectedItems as $id => $isSelected) {
            if ($id == $this->serviceRole->id) {
                $this->isSelected = $isSelected;
                if ($this->isSelected) {
                    $this->isEditing = true;
                } else {
                    $this->isEditing = false;
                }
                $this->dispatch('show-toast', [
                    'message' => 'Edit mode toggled ' . ($this->isEditing ? 'on' : 'off') . ' for ' . $this->serviceRole->name,
                    'type' => 'success'
                ]);
            }
        }
    }

    public function deleteServiceRole($id)
    {
        try {
            // dd($id);
            if ($id == $this->serviceRole->id) {
                ServiceRole::destroy($id);
                $this->dispatch('show-toast', [
                    'message' => 'Service Role deleted successfully.',
                    'type' => 'success'
                ]);
            }

            // $url = route('svcroles');
            // header("Location: $url");
            // exit();
            // } else {
            //     $this->dispatch('show-toast', [
            //         'message' => 'Service Role not found.',
            //         'type' => 'error'
            //     ]);
            // }
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'An error occurred: ' . $e->getMessage(),
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
            'id' => $serviceRoleId,
            'model' => 'svcr_item_delete'
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

    public function updateModalId($id) {
        $this->dispatch('update-modal-id', [
            'id' => $id
        ]);
    }
}

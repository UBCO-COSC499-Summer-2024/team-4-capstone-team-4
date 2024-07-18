<?php

namespace App\Livewire\Templates;

use App\Models\Area;
use App\Models\AuditLog;
use App\Models\ServiceRole;
use App\Models\User;
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
        'update-service-role' => 'saveServiceRole',
        'svcr-item-delete' => 'deleteServiceRole',
        'svcr-item-archive' => 'archiveServiceRole',
        'svcr-item-unarchive' => 'archiveServiceRole',
        'item-modal-id' => 'updateModalId',
    ];

    public function mount($serviceRoleId)
    {
        $serviceRole = ServiceRole::find($serviceRoleId);
        $this->serviceRole = $serviceRole;
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

    public function archiveServiceRole($id) {
        $audit_user = User::find((int) auth()->user()->id)->name;
        try {
            if ($id !== $this->serviceRole->id) {
                return;
            }
            $serviceRole = ServiceRole::find((int) $id);
            if ($serviceRole) {
                $isArchived = $serviceRole->archived;
                $serviceRole->archived = !$isArchived;
                $serviceRole->save();

                AuditLog::create([
                    'user_id' => (int) auth()->user()->id,
                    'user_alt' => $audit_user,
                    'action' => 'archive',
                    'table_name' => 'service_roles',
                    'operation_type' => 'UPDATE',
                    'old_value' => json_encode($serviceRole->getOriginal()),
                    'new_value' => json_encode($serviceRole->getAttributes()),
                    'description' => 'Service Role ' . $serviceRole->name . ' ' . ($isArchived ? 'unarchived' : 'archived') . ' by ' . $audit_user,
                ]);
                $this->dispatch('show-toast', [
                    'message' => 'Service role ' . ($isArchived ? 'unarchived' : 'archived') . ' successfully!',
                    'type' => 'success'
                ]);
            } else {
                throw new \Exception('Service Role not found');
            }
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'An error occurred',
                'type' => 'error'
            ]);
            AuditLog::create([
                'user_id' => (int) auth()->user()->id,
                'user_alt' => auth()->user()->name,
                'action' => 'archive',
                'table_name' => 'service_roles',
                'operation_type' => 'UPDATE',
                'description' => $e->getMessage(),
            ]);
        }
    }

    public function editServiceRole()
    {
        $this->isEditing = true;
    }

    public function saveServiceRole($id)
    {
        if ($id !== $this->serviceRole->id) {
            return;
        }
        // $this->validate([
        //     'serviceRole.name' => 'required',
        //     'serviceRole.year' => 'required|integer',
        //     'serviceRole.description' => 'nullable',
        //     'serviceRole.area_id' => 'required|exists:areas,id'
        // ]);

        // $this->serviceRole->save();
        // $this->isEditing = false;

        // // Optional: emit an event to the parent
        // $this->dispatch('show-toast', [
        //     'message' => 'Service Role updated successfully.',
        //     'type' => 'success'
        // ]);
        try {
            $this->validate([
                'serviceRole.name' => 'required',
                'serviceRole.year' => 'required|integer',
                'serviceRole.description' => 'nullable',
                'serviceRole.area_id' => 'required|exists:areas,id'
            ]);

            $audit_user = User::find((int) auth()->user()->id)->name;
            $oldValue = $this->serviceRole->getOriginal();
            $this->serviceRole->save();
            $this->isEditing = false;
            $this->dispatch('show-toast', [
                'message' => 'Service Role updated successfully.',
                'type' => 'success'
            ]);

            AuditLog::create([
                'user_id' => (int) auth()->user()->id,
                'user_alt' => $audit_user,
                'action' => 'update',
                'table_name' => 'service_roles',
                'operation_type' => 'UPDATE',
                'old_value' => json_encode($oldValue),
                'new_value' => json_encode($this->serviceRole->getAttributes()),
                'description' => 'Service Role ' . $this->serviceRole->name . ' updated by ' . $audit_user,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'An error occurred',
                'type' => 'error'
            ]);
            AuditLog::create([
                'user_id' => (int) auth()->user()->id,
                'user_alt' => auth()->user()->name,
                'action' => 'update',
                'table_name' => 'service_roles',
                'operation_type' => 'UPDATE',
                'description' => $e->getMessage(),
            ]);
        }
    }

    public function confirmSDelete($serviceRoleId)
    {
        $this->dispatch('confirmDelete', [
            'message' => 'Are you sure you want to delete this service role?',
            'id' => $serviceRoleId,
            'model' => 'svcr_item_delete'
        ]);
    }

    public function confirmSArchive($serviceRoleId) {
        $isArchived = $this->serviceRole->archived;
        $this->dispatch('confirmArchive', [
            'message' => 'Are you sure you want to '.(
                $isArchived ? 'unarchive' : 'archive'
            ).' this service role?',
            'id' => $serviceRoleId,
            'model' => $isArchived ? 'svcr_item_unarchive' : 'svcr_item_archive'
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

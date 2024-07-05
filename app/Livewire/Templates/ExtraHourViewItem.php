<?php

namespace App\Livewire\Templates;

use Livewire\Component;
use App\Models\ExtraHour;

class ExtraHourViewItem extends Component
{
    public ExtraHour $extraHour;
    public $editing = false;

    protected $rules = [
        'extraHour.name' => 'required|string|max:255',
        'extraHour.description' => 'nullable|string',
        'extraHour.hours' => 'required|integer',
        'extraHour.year' => 'required|integer|digits:4',
        'extraHour.month' => 'required|integer|min:1|max:12',
        'extraHour.assigner' => 'required|exists:user_roles,id',
        'extraHour.instructor_id' => 'nullable|exists:user_roles,id',
        'extraHour.area' => 'required|exists:areas,id',
    ];

    protected $listeners = [
        'confirm-delete' => 'confirmDelete',
        'deleteExtraHour'=> 'deleteExtraHour',
    ];

    public function mount(ExtraHour $extraHour)
    {
        $this->extraHour = $extraHour;
    }

    public function edit()
    {
        $this->editing = true;
    }

    public function update()
    {
        $this->validate();

        $this->extraHour->save();
        $this->editing = false;
    }

    public function confirmDelete($item) {
        $this->dispatch('confirm-delete', [
            'message' => 'Are you sure you want to delete this Extra Hour?',
            'model' => ExtraHour::class,
            'id' => $item->id
        ]);
    }

    public function deleteExtraHour($item) {
        $count = ExtraHour::destroy($item);
        if ($count > 0) {
            $this->dispatch('show-toast', [
                'message' => 'Extra Hour deleted successfully.',
                'type' => 'success'
            ]);
            $this->extraHour = null;
        } else {
            $this->dispatch('show-toast', [
                'message' => 'Failed to delete Extra Hour.',
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.extra-hour-view-item');
    }
}

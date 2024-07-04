<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ExtraHour;
use App\Models\ServiceRole;
use Livewire\WithPagination;

class ExtraHourView extends Component
{
    use WithPagination;

    public $serviceRoleId;
    public $show = false;
    public $extraHours;

    protected $listeners = [
        'show' => 'showView',
        'deleteExtraHour' => 'confirmDelete'
    ];

    public function mount($serviceRoleId)
    {
        $this->serviceRoleId = $serviceRoleId;
        if ($this->serviceRoleId == null) {
            $this->extraHours = [];
        } else {
            $this->extraHours = ServiceRole::find($this->serviceRoleId)->extra_hours()->paginate(10);
        }
    }

    public function showView()
    {
        $this->show = true;
    }

    public function confirmDelete($extraHourId)
    {
        $this->dispatch('confirm-delete', [
            'message' => 'Are you sure you want to delete this Extra Hour?',
            'model' => ExtraHour::class,
            'id' => $extraHourId
        ]);
    }

    public function render()
    {
        // dd($this->serviceRoleId);
        if ($this->serviceRoleId == null) {
            return view('livewire.extra-hour-view', [
                'extraHours' => []
            ]);
        }
        $this->extraHours = ServiceRole::find($this->serviceRoleId)->extra_hours()->paginate(10);
        // dd($this->extraHours);
        return view('livewire.extra-hour-view', [
            'extraHours' => $this->extraHours
        ]);
    }
}

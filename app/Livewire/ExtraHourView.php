<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ExtraHour;
use App\Models\ServiceRole;
use Livewire\WithPagination;

class ExtraHourView extends Component
{
    use WithPagination;

    // Public properties for service role ID, view state, and extra hours data
    public $serviceRoleId;
    public $show = false;
    public $extraHours;

    // Event listeners for showing view and confirming delete action
    protected $listeners = [
        'show' => 'showView',
        'deleteExtraHour' => 'confirmDelete'
    ];

    /**
     * Mount the component with the given service role ID.
     *
     * @param int|null $serviceRoleId
     * @return void
     */
    public function mount($serviceRoleId)
    {
        $this->serviceRoleId = $serviceRoleId;

        // Initialize extraHours based on serviceRoleId
        if ($this->serviceRoleId == null) {
            $this->extraHours = [];
        } else {
            $this->extraHours = ServiceRole::find($this->serviceRoleId)
                ->extra_hours()
                ->paginate(10);
        }
    }

    /**
     * Set the show property to true to display the view.
     *
     * @return void
     */
    public function showView()
    {
        $this->show = true;
    }

    /**
     * Dispatch an event to confirm deletion of an extra hour.
     *
     * @param int $extraHourId
     * @return void
     */
    public function confirmDelete($extraHourId)
    {
        $this->dispatch('confirm-delete', [
            'message' => 'Are you sure you want to delete this Extra Hour?',
            'model' => ExtraHour::class,
            'id' => $extraHourId
        ]);
    }

    /**
     * Render the component view with the extra hours data.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Check if serviceRoleId is null and handle accordingly
        if ($this->serviceRoleId == null) {
            return view('livewire.extra-hour-view', [
                'extraHours' => []
            ]);
        }

        // Fetch and paginate extra hours for the given serviceRoleId
        $this->extraHours = ServiceRole::find($this->serviceRoleId)
            ->extra_hours()
            ->paginate(10);

        return view('livewire.extra-hour-view', [
            'extraHours' => $this->extraHours
        ]);
    }
}


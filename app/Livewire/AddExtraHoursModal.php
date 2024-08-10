<?php

namespace App\Livewire;

use Livewire\Component;

class AddExtraHoursModal extends Component
{
    /**
     * Indicates whether the modal is visible.
     *
     * @var bool
     */
    public $showModal = false;

    /**
     * Listens for the 'openModal' event to trigger the open method.
     *
     * @var array
     */
    protected $listeners = ['openModal' => 'open'];

    /**
     * Sets the showModal property to true to display the modal.
     *
     * @return void
     */
    public function open()
    {
        $this->showModal = true;
    }

    /**
     * Saves the data and hides the modal.
     *
     * Add the logic to save extra hours data here.
     *
     * @return void
     */
    public function save()
    {
        // Save logic here

        $this->showModal = false;
    }

    /**
     * Renders the view for the add-extra-hours-modal component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.add-extra-hours-modal');
    }
}

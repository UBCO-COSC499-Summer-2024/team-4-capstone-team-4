<?php

namespace App\Livewire;

use Livewire\Component;

class AddExtraHoursModal extends Component
{
    public $showModal = false;

    protected $listeners = ['openModal' => 'open'];
    public function open()
    {
        $this->showModal = true;
    }

    public function save()
    {
        // Save logic here

        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.add-extra-hours-modal');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;

class ImportConfirmModal extends Component
{
    public $duplicateCourses = [];

    public function render()
    {
        return view('livewire.import-confirm-modal');
    }
}

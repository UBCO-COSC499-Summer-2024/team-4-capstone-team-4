<?php

namespace App\Livewire;

use Livewire\Component;

class UploadFileFormSei extends Component
{
    public $finalCSVs = [];

    public function render()
    {
        return view('livewire.upload-file-form-sei');
    }
}

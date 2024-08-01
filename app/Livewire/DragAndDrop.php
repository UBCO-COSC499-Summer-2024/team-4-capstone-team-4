<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
// use Livewire\WithFileUploads;

class DragAndDrop extends Component
{
    // use WithFileUploads;
    public $files;
    public $action;
    public $accept;
    public $multiple;
    public $externalHandler;

    public function mount($action = null, $accept = '.csv', $multiple = true, $externalHandler = true) {
        $this->files = collect();
        $this->action = $action ?? 'upload.file';
        $this->accept = $accept;
        $this->multiple = $multiple;
        $this->externalHandler = $externalHandler;
    }

    public function render()
    {
        return view('livewire.drag-and-drop', [
            'files' => $this->files,
        ]);
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class DragAndDrop extends Component
{
    use WithFileUploads;
    public $files;
    public $action;
    public $accept;
    public $multiple;
    // public $uploadStatus = '';

    public function mount($action = null, $accept = '.csv', $multiple = true) {
        $this->files = collect();
        $this->action = $action ?? route('upload.file');
        $this->accept = $accept;
        $this->multiple = $multiple;
    }

    public function onFilesUploaded($files) {
        $this->files = array_merge($this->files, $files);
    }

    public function uploadFiles()
    {
        $this->validate([
            'files.*' => 'required|file|mimes:csv,xlsx,xls,json|max:2048',
        ]);

        // $this->uploadStatus = 'Uploading...';
    }

    public function removeFile($index)
    {
        unset($this->files[$index]);
        $this->files = array_values($this->files);
    }

    public function render()
    {
        return view('livewire.drag-and-drop', [
            'files' => $this->files,
            // 'uploadStatus' => $this->uploadStatus,
        ]);
    }
}

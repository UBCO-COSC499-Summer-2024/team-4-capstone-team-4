<?php

namespace App\Livewire\Components;

use Livewire\Component;

class DragAndDrop extends Component
{
    protected $files;

    public function mount() {
        $this->files = collect();
    }

    public function render() {
        return view('livewire.components.drag-and-drop', [
            'files' => $this->files,
        ]);
    }
}

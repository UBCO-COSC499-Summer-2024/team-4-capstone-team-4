<?php

namespace App\Livewire;

use Livewire\Component;

class DragAndDrop extends Component
{
    protected $files;

    public function mount() {
        $this->files = collect();
    }

    public function render()
    {
        return view('livewire.drag-and-drop');
    }
}

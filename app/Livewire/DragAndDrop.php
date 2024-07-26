<?php

namespace App\Livewire;

use Livewire\Component;

class DragAndDrop extends Component
{
    protected $files;
    public string $route;

    public function mount(string $route) {
        $this->files = collect();
        $this->route = $route;
    }

    public function render()
    {
        return view('livewire.drag-and-drop');
    }
}

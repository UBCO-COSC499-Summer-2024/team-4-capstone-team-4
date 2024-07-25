<?php

namespace App\Livewire\Templates;

use Livewire\Component;

class HelpResultItem extends Component
{
    public $result;

    public function mount($result)
    {
        // dd($result);
        $this->result = $result;
    }

    public function render()
    {
        return view('livewire.templates.help-result-item');
    }
}

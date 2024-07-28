<?php

namespace App\Livewire;

use Livewire\Component;

class ServiceRoleTable extends Component
{
    public $svcroles;

    public function mount($svcroles = []) {
        $this->svcroles = count($svcroles) > 0 ? $svcroles['formattedData'] : $svcroles;
    }

    public function render()
    {
        return view('livewire.service-role-table', [
            'svcroles' => $this->svcroles,
        ]);
    }
}

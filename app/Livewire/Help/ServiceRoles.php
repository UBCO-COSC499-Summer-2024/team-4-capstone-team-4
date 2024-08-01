<?php

namespace App\Livewire\Help;

use Livewire\Component;

class ServiceRoles extends Component
{
    public $topic;
    public $data;
    public $slot;

    public function mount($topic, $data)
    {
        $this->topic = $topic;
        $this->data = $data;
    }

    public function render()
    {
        return view('livewire.help.service-roles');
    }
}

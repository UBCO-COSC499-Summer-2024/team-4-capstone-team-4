<?php

namespace App\Livewire\Help;

use Livewire\Component;

class MainTopics extends Component
{
    public $topics;

    public function mount()
    {
        $this->topics = json_decode(file_get_contents(base_path('/resources/json/help/index.json')), true);
    }

    public function render()
    {
        return view('livewire.help.main-topics');
    }
}

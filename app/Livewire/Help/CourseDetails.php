<?php

namespace App\Livewire\Help;

use Livewire\Component;

class CourseDetails extends Component
{
    public $data;
    public $topic;
    public function render()
    {
        return view('livewire.help.course-details');
    }
}

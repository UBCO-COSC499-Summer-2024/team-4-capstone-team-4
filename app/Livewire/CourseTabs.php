<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Session;

class CourseTabs extends Component
{
    #[Session]
    public $activeTab = 'courses';
    
    public function setActiveTab($tab) {
        $this->activeTab = $tab;      
    }
   
    public function render()
    {
        return view('livewire.course-tabs');
    }
}

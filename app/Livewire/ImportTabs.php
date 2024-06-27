<?php

namespace App\Livewire;

use Livewire\Attributes\Session;
use Livewire\Component;

class ImportTabs extends Component
{

    #[Session]
    public $activeTab = 'workday';
    
    public function setActiveTab($tab) {
        $this->activeTab = $tab;      
    }
   
    public function render()
    {
        return view('livewire.import-tabs');
    }
}

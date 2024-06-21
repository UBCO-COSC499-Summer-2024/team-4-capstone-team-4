<?php

namespace App\Livewire;

use Livewire\Component;

class ImportTabs extends Component
{
    public $activeTab = 'file';

    public function setActiveTab($tab) {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.import-tabs');
    }
}

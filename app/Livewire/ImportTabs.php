<?php

namespace App\Livewire;

use Livewire\Attributes\Session;
use Livewire\Component;

class ImportTabs extends Component
{
    #[Session]
    public $activeTab = 'workday';  // The currently active tab, defaulting to 'workday'
    
    /**
     * Set the active tab.
     *
     * @param string $tab The name of the tab to activate.
     * @return void
     */
    public function setActiveTab($tab) {
        $this->activeTab = $tab;  // Update the active tab property
    }
   
    /**
     * Render the Livewire component view.
     *
     * @return \Illuminate\View\View The view for the component.
     */
    public function render()
    {
        return view('livewire.import-tabs');  // Return the view for this component
    }
}

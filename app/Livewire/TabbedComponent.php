<?php

namespace App\Livewire;

use Livewire\Component;

class TabbedComponent extends Component
{
    public $groupId = 'default';
    public $selectedTab = 'tab1';
    public $tabs = [
        'tab1' => [
            'id' => 'tab1',
            't  itle' => 'Tab 1',
            'icon' => 'home',
            'active' => true,
        ],
        'tab2' => [
            'id' => 'tab2',
            'title' => 'Tab 2',
            'icon' => 'settings',
            'active' => false,
        ],
    ];

    public $panels = [
        'tab1' => [
            'id' => 'panel1',
            'content' => 'Content for Tab 1',
        ],
        'tab2' => [
            'id' => 'panel2',
            'content' => 'Content for Tab 2',
        ],
    ];

    public function mount($groupId)
    {
        $this->groupId = $groupId;
    }

    public function selectTab($tabId)
    {
        foreach ($this->tabs as $key => &$tab) {
            $tab['active'] = ($key === $tabId);
        }
        $this->selectedTab = $tabId;
    }
    public function render()
    {
        return view('livewire.tabbed-component');
    }
}

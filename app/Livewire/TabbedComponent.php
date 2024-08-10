<?php

namespace App\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class TabbedComponent extends Component
{
    public $groupId = 'default';
    public $selectedTab = 'tab1';
    protected $listeners = ['selectTab'];
    public $tabs = [
        'tab1' => [
            'id' => 'tab1',
            'title' => 'Tab 1',
            'icon' => 'home',
        ],
        'tab2' => [
            'id' => 'tab2',
            'title' => 'Tab 2',
            'icon' => 'settings',
        ],
    ];

    public $panels = [
        'tab1' => [
            'id' => 'panel1',
            'for' => 'tab1',
            'content' => 'Content for Tab 1',
        ],
        'tab2' => [
            'id' => 'panel2',
            'for' => 'tab2',
            'content' => 'Content for Tab 2',
        ],
    ];

    public function mount($groupId)
    {
        $this->groupId = $groupId;
    }

    public function selectTab($tabId)
    {
        dd($tabId);
        Log::info('Tab selected: ' . $tabId);
        $this->selectedTab = $tabId;
    }
    public function render()
    {
        return view('livewire.tabbed-component');
    }
}

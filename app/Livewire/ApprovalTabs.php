<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Request;
use Livewire\Component;

/**
 * Class ApprovalTabs
 * 
 * Handles the tab navigation for the approval system, including the management of active tabs and tab changes.
 */
class ApprovalTabs extends Component
{
    /**
     * The list of available tabs with their associated labels, components, and options.
     *
     * @var array
     */
    public $tabs = [
        'approvals' => [
            'label' => 'Approvals',
            'component' => 'approvals',
            'options' => []
        ],
        'history' => [
            'label' => 'History',
            'component' => 'approval-histories',
            'options' => []
        ],
        'approval_types' => [
            'label' => 'Approval Types',
            'component' => 'approval-types',
            'options' => []
        ],
        'approval_statuses' => [
            'label' => 'Approval Statuses',
            'component' => 'approval-statuses',
            'options' => []
        ]
    ];

    /**
     * The currently active tab.
     *
     * @var string
     */
    public $activeTab = 'approvals';

    /**
     * The list of event listeners for this component.
     *
     * @var array
     */
    protected $listeners = [
        'tab-changed' => 'changeTab'
    ];

    /**
     * Initialize the component with the given active tab.
     *
     * @param string $activeTab
     * @return void
     */
    public function mount($activeTab = 'approvals')
    {
        $this->activeTab = $activeTab;
        // Uncomment the following line if you want to set the active tab from the request parameter.
        // $this->activeTab = Request::get('tab');
    }

    /**
     * Change the active tab to the specified tab.
     *
     * @param string $tab
     * @return void
     */
    public function changeTab($tab)
    {
        // Uncomment the following line for debugging purposes to see the current and new active tabs.
        // dd($this->activeTab, $tab);
        $this->activeTab = $tab;
    }

    /**
     * Render the view for this component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.approval-tabs');
    }
}

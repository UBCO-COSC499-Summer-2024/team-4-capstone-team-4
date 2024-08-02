<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Request;
use Livewire\Component;

class ApprovalTabs extends Component
{
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
    public $activeTab = 'approvals';
    public function mount() {
        $this->activeTab = Request::get('tab', 'approvals');
    }
    public function render() {
        return view('livewire.approval-tabs');
    }
}

<?php

namespace App\Livewire\Templates;

use Livewire\Component;

class ApprovalListItem extends Component {
    public $approval;
    public $type = 'all'; // status
    public $isEditing = false;

    public function mount($approval, $type) {
        $this->approval = $approval;
        $this->type = $type;
    }

    public function render() {
        return view('livewire.templates.approval-list-item');
    }
}

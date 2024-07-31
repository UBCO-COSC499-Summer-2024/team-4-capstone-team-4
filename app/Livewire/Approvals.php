<?php

namespace App\Livewire;

use App\Models\ApprovalStatus;
use App\Models\ApprovalType;
use Livewire\Component;

class Approvals extends Component
{
    public $types = [];
    public $viewMode = 'all'; // grid/single
    public $viewing = []; // active types based on view mode, if all, this can be empty, if grid, all in a 2 by X grid format, if single, only one type

    public function mount() {
        $this->types = ApprovalStatus::all();
        $this->viewing = $this->types;
    }

    public function changeViewMode($mode) {
        $this->viewMode = $mode;
        if ($mode === 'all') {
            $this->viewing = $this->types;
        } else {
            $this->viewing = [$mode];
        }
    }

    public function render() {
        return view('livewire.approvals');
    }
}

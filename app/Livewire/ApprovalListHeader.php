<?php

namespace App\Livewire;

use Livewire\Component;

class ApprovalListHeader extends Component
{
    public $headers = [];
    public $type = 'all';
    public $selectedSort = 'id';
    public $selectedSortOrder = 'desc';

    public $listeners = [
        'refresh-list' => 'refresh',
        'sort-selected' => 'sortSelected',
    ];

    public function mount($headers, $type) {
        $this->headers = $headers;
        $this->type = $type;
    }

    public function sortColumn($column) {
        if ($this->selectedSort === $column) {
            $this->selectedSortOrder = $this->selectedSortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->selectedSort = $column;
            $this->selectedSortOrder = 'desc';
        }
        $this->dispatch('sort-selected', [$this->selectedSort, $this->selectedSortOrder, $this->type]);
    }

    public function render() {
        return view('livewire.approval-list-header');
    }
}

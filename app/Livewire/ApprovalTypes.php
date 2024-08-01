<?php

namespace App\Livewire;

use App\Models\ApprovalType;
use Livewire\Component;
use Livewire\WithPagination;

class ApprovalTypes extends Component {
    use WithPagination;
    public $search = '';
    public $selectedSort = 'id';
    public $selectedSortOrder = 'desc';
    public $selectAll = false;
    public $selectedItems = [];
    public $selectedFilter = [];
    public $filters = [];
    public $headers = [];
    public $pgSize = 10;
    public $pgTag = 'apt_page';
    public $itemOptions = [
        'edit' => true,
        'delete' => true,
    ];

    public $listeners = [
        'refresh-list' => 'refresh',
        'sort-selected' => 'sortSelected',
        'change-filters' => 'updateFilters',
        'clear-filters' => 'clearFilters',
    ];

    public function mount() {
        $this->getHeaders();
        $this->getFilters();
    }

    public function getHeaders() {
        $this->headers = ApprovalType::getColumns();
        $this->headers = collect($this->headers)->map(function ($header) {
            return [
                'name' => $header,
                'type' => 'text',
                'label' => $header === 'id' ? 'ID' : ucwords(str_replace('_', ' ', $header))
            ];
        })->reject(function ($header) {
            return in_array($header['name'], ['created_at', 'updated_at']);
        });
    }

    public function render() {
        $query = ApprovalType::query();
        $query->orderBy($this->selectedSort, $this->selectedSortOrder);
        if ($this->search) {
            $query->where('id', 'like', '%'.$this->search.'%');
        }
        $types = $query->paginate($this->pgSize, ['*'], $this->pgTag);
        return view('livewire.approval-types', [
            'types' => $types
        ]);
    }

    public function sortColumn($column) {
        if ($this->selectedSort === $column) {
            $this->selectedSortOrder = $this->selectedSortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->selectedSort = $column;
            $this->selectedSortOrder = 'desc';
        }
        $this->dispatch('sort-selected', [$this->selectedSort, $this->selectedSortOrder]);
    }

    public function getFilters() {
        foreach ($this->headers as $header) {
            $this->filters[$header['label']] = [];
            $this->selectedFilter[$header['label']] = [];
        }
    }
}

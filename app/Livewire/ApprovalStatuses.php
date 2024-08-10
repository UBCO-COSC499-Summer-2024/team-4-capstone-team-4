<?php

namespace App\Livewire;

use App\Models\ApprovalStatus;
use Livewire\Component;
use Livewire\WithPagination;

class ApprovalStatuses extends Component {
    use WithPagination;

    public $search = '';
    public $selectedSort = 'id';
    public $selectedSortOrder = 'desc';
    public $selectAll = false;
    public $selectedItems = [];
    public $selectedFilter = [];
    public $ignore_headers = [
        'created_at', 'updated_at', 'status_id', 'approved_at', 'rejected_at', 'approved_by', 'rejected_by'
    ];
    public $filters = [];
    public $headers = [];
    public $pgSize = 10;
    public $pgTag = 'aps_page';
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
        $this->headers = ApprovalStatus::getColumns();
        $this->headers = collect($this->headers)->map(function ($header) {
            return [
                'name' => $header,
                'sort' => null,
                'filter' => null,
                'type' => 'text',
                'label' => $header === 'id' ? 'ID' : ucwords(str_replace('_', ' ', $header))
            ];
        })->reject(function ($header) {
            return in_array($header['name'], $this->ignore_headers);
        });
    }

    public function getProperColumn($column) {
        return $column === 'ID' ? 'id' : strtolower(str_replace(' ', '_', $column));
    }

    public function getFilters() {
        $this->filters = collect($this->headers)->map(function ($header) {
            $col = $this->getProperColumn($header['label']);
            return [
                'label' => $header['label'],
                'values' => ApprovalStatus::select($col)->distinct()->get()->pluck($col)
            ];
        })->reject(function ($filter) {
            return in_array($this->getProperColumn($filter['label']), ['created_at', 'updated_at', 'id', 'status_id']);
        });
        $this->filters = collect($this->filters)->mapWithKeys(function ($filter) {
            return [$filter['label'] => $filter['values']];
        });
        foreach ($this->filters as $label => $values) {
            $this->selectedFilter[$label] = [];
        }
    }

    public function sortSelected($column) {
        $this->sortColumn($column);
    }

    public function updateSearch($value) {
        $this->search = $value;
    }

    public function clearFilters() {
        $this->selectedFilter = [];
        $this->getFilters();
        foreach ($this->filters as $filter) {
            $this->selectedFilter[$filter['label']] = [];
        }
    }

    public function updateFiltres($category, $item, $isChecked) {
        if ($this->selectedFilter[$category] === null) {
            $this->selectedFilter[$category] = [];
        }
        if ($isChecked) {
            $this->selectedFilter[$category][] = $item;
        } else {
            $key = array_search($item, $this->selectedFilter[$category]);
            if ($key !== false) {
                unset($this->selectedFilter[$category][$key]);
            }
        }
        $this->selectedFilter[$category] = array_values($this->selectedFilter[$category]);
    }

    public function render() {
        $query = ApprovalStatus::query();
        $query->orderBy($this->selectedSort, $this->selectedSortOrder);

        if ($this->search) {
            $query->where('id', 'like', '%'.$this->search.'%');
        }

        $statuses = $query->paginate($this->pgSize, ['*'], $this->pgTag);

        return view('livewire.approval-statuses', [
            'statuses' => $statuses
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
}

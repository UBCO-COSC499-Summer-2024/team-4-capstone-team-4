<?php

namespace App\Livewire;

use App\Models\Approval;
use App\Models\ApprovalStatus;
use App\Models\ApprovalType;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class ApprovalList extends Component
{
    public $approvals = [];
    public $type = 'all';
    public $headers = [];
    public $ignore_headers = [
        'created_at', 'updated_at', 'status_id', 'approved_at', 'rejected_at', 'approved_by', 'rejected_by'
    ];
    public $selectedFilter = [];
    public $selectedSort;
    public $filters = [];
    public $selectedSortOrder = 'desc';
    public $query;
    public $perPage = 10;
    public $page_var = 'page';
    public $selectAll = false;
    public $selectedItems = [];

    public $listeners = [
        'refresh-list' => 'refresh',
        'sort-selected' => 'sortSelected',
        'change-filters' => 'updateFiltres',
        'clear-filters' => 'clearFilters',
    ];

    public function mount($type) {
        $this->type = $type;
        $this->page_var = $type.'_page';
        $this->getApprovals();
        $this->getHeaders();
        $this->getFilters();
    }

    public function getApprovals() {
        $this->approvals = Approval::all() ?? [];
        if ($this->type !== 'all') {
            $approvalType = ApprovalStatus::where('name', $this->type)->first();
            $this->approvals = $approvalType->approvals ?? [];
        }
    }

    public function getHeaders() {
        $this->headers = Approval::getColumns();
        $this->headers = collect($this->headers)->map(function ($header) {
            return [
                'name' => $header,
                'sort' => null,
                'filter' => null,
                'type' => 'text',
                'label' => $header === 'id' ? 'ID' : ucwords(str_replace('_', ' ', $header))
            ];
        })->reject(function ($header) {
            if ($this->type === 'all') {
                // remove status_id from the ignore_headers
                $this->ignore_headers = array_diff($this->ignore_headers, ['status_id', 'approved_at', 'rejected_at', 'approved_by', 'rejected_by']);
            }
            return in_array($header['name'], $this->ignore_headers);
        });
    }

    public function getProperColumn($column) {
        return $column === 'ID' ? 'id' : strtolower(str_replace(' ', '_', $column));
    }

    public function getFilters() {
        $this->filters = $this->headers->map(function ($header) {
            $col = $this->getProperColumn($header['label']);
            return [$header['label'] => Approval::select($col)->distinct()->get()->pluck($col)];
        })
        // ignore the timestamps
        ->reject(function ($filter) {
            $key = key($filter);
            return in_array($this->getProperColumn($key), ['created_at', 'updated_at', 'id', 'status_id']);
        });
        // convert to key = value
        $this->filters = $this->filters->mapWithKeys(function ($filter) {
            return $filter;
        });
        foreach ($this->filters as $filter) {
            $this->selectedFilter[key($filter)] = [];
        }
    }

    public function sortSelected($column) {
        $this->sortColumn($column);
    }

    public function updateSearch($value) {
        $this->query = $value;
    }

    public function refresh() {
        $this->getApprovals();
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

    public function sortColumn($column) {
        if ($this->selectedSort === $column) {
            $this->selectedSortOrder = $this->selectedSortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->selectedSort = $column;
            $this->selectedSortOrder = 'desc';
        }
        $this->dispatch('sort-selected', [$this->selectedSort, $this->selectedSortOrder]);
    }

    public function render() {
        $query = Approval::query();


        // 'user_id', 'approval_type_id', 'status_id', 'approved_at', 'rejected_at', 'details', 'approved_by', 'active', 'rejected_by'
        // with user, approval type, status, approved by -> user, rejected by -> user
        if ($this->query) {
            $query->where(function ($q) {
                $q->where('user', 'like', '%'.$this->query.'%')
                    ->orWhere('approval_type', 'like', '%'.$this->query.'%')
                    ->orWhere('approved_by', 'like', '%'.$this->query.'%')
                    ->orWhere('rejected_by', 'like', '%'.$this->query.'%')
                    ->orWhere('details', 'like', '%'.$this->query.'%');
            });
        }

        foreach($this->selectedFilter as $category => $items) {
            if (!empty($items)) {
                $query->whereIn($this->getProperColumn($category), $items);
            }
        }

        if (count($this->approvals) > 0) {
            $query->orderBy($this->selectedSort, $this->selectedSortOrder);
        }

        $approvals = $query->paginate($this->perPage);

        return view('livewire.approval-list', [
            'approvals' => $approvals
        ]);
    }
}

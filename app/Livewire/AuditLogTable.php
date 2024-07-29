<?php

namespace App\Livewire;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogTable extends Component {
    use WithPagination;

    public $auditLogs;
    public $viewMode = 'table';
    public $perpage = 20;
    public $selectAll = false;
    public $selectedItems = [];
    public $selectedFilter = [];
    public $selectedSort = 'id';
    public $selectedSortOrder = 'desc';
    public $filters = [
        "Users" => [],
        "Actions" => [],
        "Schemas" => [],
        "Operations" => []
    ];
    public $sort = [
        "ID" => "id",
        "User" => "user",
        "Action" => "action",
        "Description" => "description",
        "Schema" => "schema",
        "Operation" => "operation",
        "Created" => "created_at",
        "Updated" => "updated_at"
    ];
    public $sortOrder = [
        "Ascending" => "asc",
        "Descending" => "desc"
    ];
    public $searchQuery = "";
    protected $listeners = [
        'change-filter' => 'changeFilter',
        'change-sort' => 'changeSort',
        'change-view-mode' => 'changeViewMode',
        'change-perpage' => 'changePerpage',
        'change-search-query' => 'changeSearchQuery',
        'clear-filters' => 'clearFilters',
    ];

    public function clearFilters() {
        $this->selectedFilter = [
            "Users" => [],
            "Actions" => [],
            "Schemas" => [],
            "Operations" => []
        ];
        $this->applyFilters(); // Reapply filters after clearing
    }

    public function changeFilter($filter, $value) {
        $this->selectedFilter[$filter] = $value;
        $this->applyFilters();
    }

    public function changeSort($sort) {
        $this->selectedSort = $sort;
        $this->applyFilters();
    }

    public function changeViewMode($mode) {
        $this->viewMode = $mode;
    }

    public function changePerpage($perpage) {
        $this->perpage = $perpage;
        $this->applyFilters();
    }

    public function changeSearchQuery($query) {
        $this->searchQuery = $query;
        $this->applyFilters();
    }

    public function mount() {
        $this->populateFilters();
    }

    public function populateFilters() {
        $this->filters['Users'] = AuditLog::with('user')->select('user_id')->distinct()->get()->pluck('user');
        $this->filters['Actions'] = AuditLog::select('action')->distinct()->get()->pluck('action');
        $this->filters['Schemas'] = AuditLog::select('table_name')->distinct()->get()->pluck('table_name');
        $this->filters['Operations'] = AuditLog::select('operation_type')->distinct()->get()->pluck('operation_type');
    }

    public function sortColumn($column) {
        if ($this->selectedSort == $column) {
            $this->selectedSortOrder = $this->selectedSortOrder == 'asc' ? 'desc' : 'asc';
        } else {
            $this->selectedSort = $column;
            $this->selectedSortOrder = 'desc';
        }
    }

    public function applyFilters() {
        $response = $this->call('AuditLogController@filter', ['filters' => $this->selectedFilter]);
        // Handle response if needed
    }


    public function applyFilters() {
        $query = AuditLog::query();

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('user', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('action', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('table_name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('operation_type', 'like', '%' . $this->searchQuery . '%');
            });
        }

        foreach ($this->selectedFilter as $category => $values) {
            if (!empty($values)) {
                switch ($category) {
                    case 'Users':
                        $query->where(function ($q) use ($values) {
                            $q->whereIn('user_id', $values)
                              ->orWhereIn('user_alt', $values);
                        });
                        break;
                    case 'Actions':
                        $query->whereIn('action', $values);
                        break;
                    case 'Schemas':
                        $query->whereIn('table_name', $values);
                        break;
                    case 'Operations':
                        $query->whereIn('operation_type', $values);
                        break;
                }
            }
        }

        if ($this->selectedSort) {
            $query->orderBy($this->selectedSort, $this->selectedSortOrder);
        }

        $this->auditLogs = $query->paginate($this->perpage);
    }

    public function render() {
        return view('livewire.audit-log-table', [
            'viewMode' => $this->viewMode,
            'auditLogs' => $this->auditLogs,
            'filters' => $this->filters,
            'sort' => $this->sort,
            'sortOrder' => $this->sortOrder,
        ]);
    }
}

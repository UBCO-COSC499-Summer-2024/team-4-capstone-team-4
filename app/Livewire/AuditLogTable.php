<?php

namespace App\Livewire;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogTable extends Component
{
    use WithPagination;

    public $viewMode = 'table';
    public $perpage = 20;
    public $selectAll = false;
    public $selectedItems = [];
    public $selectedFilter = [
        "Users" => [],
        "Actions" => [],
        "Schemas" => [],
        "Operations" => []
    ];
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

    public function mount()
    {
        $this->populateFilters();
    }

    public function clearFilters()
    {
        $this->selectedFilter = [
            "Users" => [],
            "Actions" => [],
            "Schemas" => [],
            "Operations" => []
        ];
        $this->applyFilters();
    }

    public function changeFilter($filter, $value, $checked)
    {
        if ($checked) {
            $this->selectedFilter[$filter][] = $value;
        } else {
            if (($key = array_search($value, $this->selectedFilter[$filter])) !== false) {
                unset($this->selectedFilter[$filter][$key]);
            }
        }
        $this->applyFilters();
    }

    public function changeSort($sort)
    {
        $this->selectedSort = $sort;
        $this->applyFilters();
    }

    public function changeViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function changePerpage($perpage)
    {
        $this->perpage = $perpage;
        $this->applyFilters();
    }

    public function changeSearchQuery($query)
    {
        $this->searchQuery = $query;
        $this->applyFilters();
    }

    public function populateFilters()
    {
        $filters = [];

        $userIds = AuditLog::select('user_id')
            ->whereNotNull('user_id')
            ->distinct()
            ->get()
            ->pluck('user_id');

        $userAlts = AuditLog::select('user_alt')
            ->whereNotNull('user_alt')
            ->distinct()
            ->get()
            ->pluck('user_alt');

        $combinedUsers = $userIds->merge($userAlts)->unique()->sort();

        $filters['Users'] = $combinedUsers;
        $filters['Actions'] = AuditLog::select('action')->distinct()->get()->pluck('action');
        $filters['Schemas'] = AuditLog::select('table_name')->distinct()->get()->pluck('table_name');
        $filters['Operations'] = AuditLog::select('operation_type')->distinct()->get()->pluck('operation_type');

        $this->filters = $filters;
    }

    public function sortColumn($column)
    {
        if ($this->selectedSort == $column) {
            $this->selectedSortOrder = $this->selectedSortOrder == 'asc' ? 'desc' : 'asc';
        } else {
            $this->selectedSort = $column;
            $this->selectedSortOrder = 'desc';
        }
    }

    public function applyFilters()
    {
        // Moved the query building and pagination to the render method
    }

    public function render()
    {
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

        foreach ($this->selectedFilter as $category => $items) {
            if (!empty($items)) {
                $query->whereIn($this->getFilterColumn($category), $items);
            }
        }

        $query->orderBy($this->selectedSort, $this->selectedSortOrder);

        $auditLogs = $query->paginate($this->perpage);

        return view('livewire.audit-log-table', [
            'auditLogs' => $auditLogs,
        ]);
    }

    private function getFilterColumn($category)
    {
        switch ($category) {
            case 'Users':
                return 'user';
            case 'Actions':
                return 'action';
            case 'Schemas':
                return 'table_name';
            case 'Operations':
                return 'operation_type';
            default:
                return null;
        }
    }
}

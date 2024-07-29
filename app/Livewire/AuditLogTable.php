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
        'change-filter' => 'updateFilter',
        'change-sort' => 'changeSort',
        'change-view-mode' => 'changeViewMode',
        'change-perpage' => 'changePerpage',
        'change-search-query' => 'changeSearchQuery',
        'clear-filters' => 'clearFilters',
    ];

    public function mount() {
    }

    public function clearFilters()
    {
        $this->selectedFilter = [
            "Users" => [],
            "Actions" => [],
            "Schemas" => [],
            "Operations" => []
        ];
    }

    public function updateFilter($category, $item, $isChecked) {
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

    public function changeSort($sort)
    {
        $this->selectedSort = $sort;
    }

    public function changeViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function changePerpage($perpage)
    {
        $this->perpage = $perpage;
    }

    public function changeSearchQuery($query)
    {
        $this->searchQuery = $query;
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

    public function render()
    {
        $query = AuditLog::query();

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('user_id', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('user_alt', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('action', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('description', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('table_name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('operation_type', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('old_value', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('new_value', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('created_at', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('updated_at', 'like', '%' . $this->searchQuery . '%');
            });
        }

        foreach ($this->selectedFilter as $category => $items) {
            if (!empty($items)) {
                if ($category == 'Users') {
                    $query->where(function ($q) use ($items) {
                        foreach ($items as $item) {
                            if (is_numeric($item)) {
                                $q->orWhere('user_id', $item);
                            } else {
                                $q->orWhere('user_alt', $item);
                            }
                        }
                    });
                } else {
                    $query->whereIn($this->getFilterColumn($category), $items);
                }
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
                // user_id and user_alt
                return ['user_id', 'user_alt'];
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

<?php

namespace App\Livewire;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogTable extends Component
{
    use WithPagination;

    // View mode: table or other
    public $viewMode = 'table';

    // Number of items per page
    public $perpage = 20;

    // State of the 'select all' checkbox
    public $selectAll = false;

    // Array to keep track of selected items
    public $selectedItems = [];

    // Array to hold selected filters
    public $selectedFilter = [
        "Users" => [],
        "Actions" => [],
        "Schemas" => [],
        "Operations" => []
    ];

    // Currently selected sort column
    public $selectedSort = 'id';

    // Order of sorting: ascending or descending
    public $selectedSortOrder = 'desc';

    // Available sort options
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

    // Available sort orders
    public $sortOrder = [
        "Ascending" => "asc",
        "Descending" => "desc"
    ];

    // Search query for filtering results
    public $searchQuery = "";

    // Listeners for Livewire events
    protected $listeners = [
        'change-filter' => 'updateFilter',
        'change-sort' => 'changeSort',
        'change-view-mode' => 'changeViewMode',
        'change-perpage' => 'changePerpage',
        'change-search-query' => 'changeSearchQuery',
        'clear-filters' => 'clearFilters',
        'preview-data' => 'previewData'
    ];

    // State for showing data preview modal
    public $showDataPreviewModal = false;

    // ID for the item being previewed
    public $showDataPreviewModalForId = null;

    // Data to display in the preview modal
    public $dataToPreview = null;

    /**
     * Initialize component properties.
     */
    public function mount() {
    }

    /**
     * Clear all selected filters.
     */
    public function clearFilters() {
        $this->selectedFilter = [
            "Users" => [],
            "Actions" => [],
            "Schemas" => [],
            "Operations" => []
        ];
    }

    /**
     * Show a preview of the data for a specific audit log item.
     *
     * @param int $id
     * @param array $data
     */
    public function previewData($id, $data) {
        $auditLog = AuditLog::find($id);
        if ($auditLog) {
            $this->showDataPreviewModal = true;
            $this->showDataPreviewModalForId = $id;
            $this->dataToPreview = json_decode(json_encode($data), JSON_PRETTY_PRINT);
        }
    }

    /**
     * Update selected filters based on the category, item, and check state.
     *
     * @param string $category
     * @param mixed $item
     * @param bool $isChecked
     */
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

    /**
     * Change the sorting column.
     *
     * @param string $sort
     */
    public function changeSort($sort) {
        $this->selectedSort = $sort;
    }

    /**
     * Change the view mode (e.g., table, card).
     *
     * @param string $mode
     */
    public function changeViewMode($mode) {
        $this->viewMode = $mode;
    }

    /**
     * Change the number of items per page.
     *
     * @param int $perpage
     */
    public function changePerpage($perpage) {
        $this->perpage = $perpage;
    }

    /**
     * Change the search query for filtering results.
     *
     * @param string|null $query
     */
    public function changeSearchQuery($query = null) {
        $this->searchQuery = $query;
    }

    /**
     * Toggle the sorting order for a given column.
     *
     * @param string $column
     */
    public function sortColumn($column) {
        if ($this->selectedSort == $column) {
            $this->selectedSortOrder = $this->selectedSortOrder == 'asc' ? 'desc' : 'asc';
        } else {
            $this->selectedSort = $column;
            $this->selectedSortOrder = 'desc';
        }
    }

    /**
     * Render the component view with paginated audit logs based on filters and sorting.
     *
     * @return \Illuminate\View\View
     */
    public function render() {
        $query = AuditLog::query();

        // Apply search query filters
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

        // Apply selected filters
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

        // Adjust sorting column if necessary
        if (strpos($this->selectedSort, 'old_') !== false) {
            $this->selectedSort = 'old_value';
        } elseif (strpos($this->selectedSort, 'new_') !== false) {
            $this->selectedSort = 'new_value';
        }
        $query->orderBy($this->selectedSort, $this->selectedSortOrder);

        // Paginate results
        $auditLogs = $query->paginate($this->perpage);

        return view('livewire.audit-log-table', [
            'auditLogs' => $auditLogs,
        ]);
    }

    /**
     * Get the filter column based on the filter category.
     *
     * @param string $category
     * @return mixed
     */
    private function getFilterColumn($category) {
        switch ($category) {
            case 'Users':
                // Return columns for user filter
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


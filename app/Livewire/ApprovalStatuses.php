<?php

namespace App\Livewire;

use App\Models\ApprovalStatus;
use Livewire\Component;
use Livewire\WithPagination;

class ApprovalStatuses extends Component {
    use WithPagination;

    public $search = '';  // Search term for filtering results
    public $selectedSort = 'id';  // Column to sort by
    public $selectedSortOrder = 'desc';  // Order of sorting (ascending or descending)
    public $selectAll = false;  // Flag for selecting all items
    public $selectedItems = [];  // Array of selected items
    public $selectedFilter = [];  // Array of selected filters
    public $ignore_headers = [
        'created_at', 'updated_at', 'status_id', 'approved_at', 'rejected_at', 'approved_by', 'rejected_by'
    ];  // Headers to ignore in filters
    public $filters = [];  // Array of filters
    public $headers = [];  // Array of table headers
    public $pgSize = 10;  // Number of items per page
    public $pgTag = 'aps_page';  // Pagination tag
    public $itemOptions = [
        'edit' => true,  // Option to edit items
        'delete' => true,  // Option to delete items
    ];

    public $listeners = [
        'refresh-list' => 'refresh',  // Listener to refresh the list
        'sort-selected' => 'sortSelected',  // Listener to handle sorting
        'change-filters' => 'updateFilters',  // Listener to handle filter changes
        'clear-filters' => 'clearFilters',  // Listener to clear filters
    ];

    /**
     * Initialize component with headers and filters.
     */
    public function mount() {
        $this->getHeaders();
        $this->getFilters();
    }

    /**
     * Get and format table headers.
     */
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

    /**
     * Convert header label to proper column name format.
     *
     * @param string $column Header label
     * @return string Column name
     */
    public function getProperColumn($column) {
        return $column === 'ID' ? 'id' : strtolower(str_replace(' ', '_', $column));
    }

    /**
     * Get distinct filter values for each column.
     */
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

    /**
     * Handle column sorting.
     *
     * @param string $column Column name to sort by
     */
    public function sortSelected($column) {
        $this->sortColumn($column);
    }

    /**
     * Update the search term.
     *
     * @param string $value New search term
     */
    public function updateSearch($value) {
        $this->search = $value;
    }

    /**
     * Clear all selected filters and reset filters.
     */
    public function clearFilters() {
        $this->selectedFilter = [];
        $this->getFilters();
        foreach ($this->filters as $filter) {
            $this->selectedFilter[$filter['label']] = [];
        }
    }

    /**
     * Update selected filters based on user interaction.
     *
     * @param string $category Filter category
     * @param mixed $item Filter item
     * @param bool $isChecked Whether the item is checked
     */
    public function updateFilters($category, $item, $isChecked) {
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

    /**
     * Render the component view with paginated approval statuses.
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * Toggle sorting direction or change sorting column.
     *
     * @param string $column Column name to sort by
     */
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


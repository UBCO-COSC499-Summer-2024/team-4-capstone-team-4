<?php

namespace App\Livewire;

use App\Models\ApprovalType;
use Livewire\Component;
use Livewire\WithPagination;

class ApprovalTypes extends Component {
    use WithPagination;

    public $search = ''; // The search query string
    public $selectedSort = 'id'; // The column to sort by
    public $selectedSortOrder = 'desc'; // The sort order ('asc' or 'desc')
    public $selectAll = false; // Flag for selecting all items
    public $selectedItems = []; // Array of selected item IDs
    public $selectedFilter = []; // Array of selected filters
    public $ignore_headers = [
        'created_at', 'updated_at', 'status_id', 'approved_at', 'rejected_at', 'approved_by', 'rejected_by'
    ]; // Columns to ignore in the header
    public $filters = []; // Array of available filters
    public $headers = []; // Array of column headers
    public $pgSize = 10; // Number of items per page
    public $pgTag = 'apt_page'; // Pagination tag
    public $itemOptions = [
        'edit' => true,
        'delete' => true,
    ]; // Options for item actions

    public $listeners = [
        'refresh-list' => 'refresh',
        'sort-selected' => 'sortSelected',
        'change-filters' => 'updateFilters',
        'clear-filters' => 'clearFilters',
    ]; // Listeners for Livewire events

    /**
     * Initialize the component.
     *
     * @return void
     */
    public function mount() {
        $this->getHeaders();
        $this->getFilters();
    }

    /**
     * Retrieve and set column headers.
     *
     * @return void
     */
    public function getHeaders() {
        $this->headers = ApprovalType::getColumns();
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
     * Convert a column label to its database column name.
     *
     * @param string $column
     * @return string
     */
    public function getProperColumn($column) {
        return $column === 'ID' ? 'id' : strtolower(str_replace(' ', '_', $column));
    }

    /**
     * Retrieve and set available filters based on column headers.
     *
     * @return void
     */
    public function getFilters() {
        $this->filters = collect($this->headers)->map(function ($header) {
            $col = $this->getProperColumn($header['label']);
            return [
                'label' => $header['label'],
                'values' => ApprovalType::select($col)->distinct()->get()->pluck($col)
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
     * Sort the data by the selected column.
     *
     * @param string $column
     * @return void
     */
    public function sortSelected($column) {
        $this->sortColumn($column);
    }

    /**
     * Update the search query string.
     *
     * @param string $value
     * @return void
     */
    public function updateSearch($value) {
        $this->search = $value;
    }

    /**
     * Clear all filters and reset to default filters.
     *
     * @return void
     */
    public function clearFilters() {
        $this->selectedFilter = [];
        $this->getFilters();
        foreach ($this->filters as $filter) {
            $this->selectedFilter[$filter['label']] = [];
        }
    }

    /**
     * Update the selected filters for a specific category.
     *
     * @param string $category
     * @param string $item
     * @param bool $isChecked
     * @return void
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
     * Render the view for the component with paginated data.
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * Set the column to sort by and toggle the sort order.
     *
     * @param string $column
     * @return void
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


<?php

namespace App\Livewire;

use App\Models\ApprovalHistory;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class ApprovalHistories
 * Livewire component for managing and displaying approval histories.
 */
class ApprovalHistories extends Component
{
    use WithPagination;

    /** @var string Search query for filtering results. */
    public $search = '';

    /** @var string Column to sort by. */
    public $selectedSort = 'id';

    /** @var string Sort order, either 'asc' or 'desc'. */
    public $selectedSortOrder = 'desc';

    /** @var bool Flag to select or deselect all items. */
    public $selectAll = false;

    /** @var array Selected filters for the data table. */
    public $selectedFilter = [];

    /** @var array Columns to ignore in the table display. */
    public $ignore_headers = [
        'created_at', 'updated_at', 'status_id', 'approved_at', 'rejected_at', 'approved_by', 'rejected_by'
    ];

    /** @var array List of selected items for bulk actions. */
    public $selectedItems = [];

    /** @var array Available filters for the table. */
    public $filters = [];

    /** @var array Table headers configuration. */
    public $headers = [];

    /** @var int Number of items per page. */
    public $pgSize = 10;

    /** @var string Pagination tag for Livewire. */
    public $pgTag = 'aph_page';

    /** @var array Options for item actions (edit, delete). */
    public $itemOptions = [
        'edit' => true,
        'delete' => true,
    ];

    /** @var array Listeners for Livewire events. */
    public $listeners = [
        'refresh-list' => 'refresh',
        'sort-selected' => 'sortSelected',
        'change-filters' => 'updateFilters',
        'clear-filters' => 'clearFilters',
    ];

    /**
     * Initialize component state.
     */
    public function mount()
    {
        $this->getHeaders();
        $this->getFilters();
    }

    /**
     * Retrieve and configure table headers.
     */
    public function getHeaders()
    {
        $this->headers = ApprovalHistory::getColumns();
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
     * Convert column label to database column name.
     *
     * @param string $column The column label.
     * @return string The corresponding database column name.
     */
    public function getProperColumn($column)
    {
        return $column === 'ID' ? 'id' : strtolower(str_replace(' ', '_', $column));
    }

    /**
     * Retrieve and configure filters based on table headers.
     */
    public function getFilters()
    {
        $this->filters = collect($this->headers)->map(function ($header) {
            $col = $this->getProperColumn($header['label']);
            return [
                'label' => $header['label'],
                'values' => ApprovalHistory::select($col)->distinct()->get()->pluck($col)
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
     * Handle sorting by the selected column.
     *
     * @param string $column The column to sort by.
     */
    public function sortSelected($column)
    {
        $this->sortColumn($column);
    }

    /**
     * Update search query value.
     *
     * @param string $value The new search query.
     */
    public function updateSearch($value)
    {
        $this->search = $value;
    }

    /**
     * Clear all selected filters and reset to default filters.
     */
    public function clearFilters()
    {
        $this->selectedFilter = [];
        $this->getFilters();
        foreach ($this->filters as $filter) {
            $this->selectedFilter[$filter['label']] = [];
        }
    }

    /**
     * Update filters based on user selection.
     *
     * @param string $category The filter category.
     * @param mixed $item The filter item.
     * @param bool $isChecked Whether the item is checked or not.
     */
    public function updateFilters($category, $item, $isChecked)
    {
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
     * Render the component view with the current state.
     *
     * @return \Illuminate\View\View The rendered view.
     */
    public function render()
    {
        $query = ApprovalHistory::query();
        $query->orderBy($this->selectedSort, $this->selectedSortOrder);
        if ($this->search) {
            $query->where('id', 'like', '%'.$this->search.'%');
        }
        $histories = $query->paginate($this->pgSize, ['*'], $this->pgTag)->appends(request()->all());
        return view('livewire.approval-histories', [
            'histories' => $histories
        ]);
    }

    /**
     * Change the sorting column and order.
     *
     * @param string $column The column to sort by.
     */
    public function sortColumn($column)
    {
        if ($this->selectedSort === $column) {
            $this->selectedSortOrder = $this->selectedSortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->selectedSort = $column;
            $this->selectedSortOrder = 'desc';
        }
        $this->dispatch('sort-selected', [$this->selectedSort, $this->selectedSortOrder]);
    }
}


<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * Class ApprovalListHeader
 * Handles the header for the approval list, including sorting functionality.
 */
class ApprovalListHeader extends Component
{
    /**
     * @var array $headers
     * List of headers for the approval list.
     */
    public $headers = [];

    /**
     * @var string $type
     * Type of the list (e.g., 'all').
     */
    public $type = 'all';

    /**
     * @var string $selectedSort
     * The column currently selected for sorting.
     */
    public $selectedSort = 'id';

    /**
     * @var string $selectedSortOrder
     * The current sort order ('asc' or 'desc').
     */
    public $selectedSortOrder = 'desc';

    /**
     * @var bool $selectAll
     * Indicates whether the 'select all' checkbox is checked.
     */
    public $selectAll = false;

    /**
     * @var array $selectedItems
     * Array of currently selected items.
     */
    public $selectedItems = [];

    /**
     * @var array $listeners
     * Event listeners for the component.
     */
    public $listeners = [
        'refresh-list' => 'refresh',
    ];

    /**
     * Initialize the component with given headers and type.
     *
     * @param array $headers
     * @param string $type
     */
    public function mount($headers, $type) {
        $this->headers = $headers;
        $this->type = $type;
    }

    /**
     * Handle sorting of columns.
     *
     * @param string $column
     */
    public function sortColumn($column) {
        if ($this->selectedSort === $column) {
            // Toggle sort order if the same column is selected.
            $this->selectedSortOrder = $this->selectedSortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            // Set new column for sorting and default to descending order.
            $this->selectedSort = $column;
            $this->selectedSortOrder = 'desc';
        }
        // Dispatch event to notify of the new sort selection.
        $this->dispatch('sort-selected', [$this->selectedSort, $this->selectedSortOrder]);
    }

    /**
     * Render the view for the component.
     *
     * @return \Illuminate\View\View
     */
    public function render() {
        return view('livewire.approval-list-header');
    }
}


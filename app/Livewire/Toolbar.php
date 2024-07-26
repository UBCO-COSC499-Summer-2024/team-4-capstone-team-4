<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Toolbar extends Component {
    public $id;
    public $class;
    public $features;
    public $order;
    public $viewModes;
    public $viewMode = 'table'; // Default view mode
    public $pageMode = 'pagination'; // Default page mode
    public $pageModes;
    public $filter;
    public $filterBy;
    public $filterValue;
    public $searchCategory; // For categorized search
    public $searchCategories;
    public $searchQuery;
    public $sort;
    public $sortBy;
    public $sortOrder;
    public $group;
    public $groupBy;
    public $actions;
    public $selectedActions = []; // For multiple actions
    public $exports;
    public $imports;
    public $data;

    protected $listeners = [
        'dropdownValueChanged' => 'handleDropdownChange',
        'applyActions' => 'applyActions',
        'resetFilters' => 'resetFilters',
        'clearSearch' => 'clearSearch'
    ];

    public function mount(
        $id = null,
        $class = null,
        $features = [
            'viewMode' => true,
            'pageMode' => true,
            'searchCategory' => true,
            'search' => true,
            'filter' => true,
            'filterValue' => true,
            'sort' => true,
            'group' => true,
            'actions' => true,
            'export' => true,
            'import' => true,
            'settings' => false,
        ],
        $order = [
            ['viewMode', 'pageMode'],
            ['searchCategory', 'search'],
            ['sort'],
            ['filter', 'filterValue'],
            ['group'],
            ['actions', 'export', 'import', 'settings'],
        ],
        $viewModes = [
            'card' => 'Card',
            'list' => 'List',
            'table' => 'Table',
        ],
        $viewMode = 'table', // Default view mode
        $pageMode = 'pagination', // Default page mode
        $pageModes = [
            'pagination' => 'Pagination',
            'infinite' => 'Infinite Scroll',
        ],
        $filterBy = [],
        $filterValue = null,
        $sortBy = [],
        $sortOrder = [
            'asc' => 'Ascending',
            'desc' => 'Descending',
        ],
        $groupBy = [],
        $actions = [
            'edit' => 'Edit',
            'delete' => 'Delete',
            'duplicate' => 'Duplicate',
            'archive' => 'Archive',
            'restore' => 'Restore',
        ],
        $exports = [
            'csv' => 'CSV',
            'pdf' => 'PDF',
            'json' => 'JSON',
        ],
        $imports = [
            'csv' => 'CSV',
            'json' => 'JSON',
        ],
        $data = [],
        $searchCategory = null,
        $searchCategories = [
            'all' => 'All',
            'title' => 'Title',
            'description' => 'Description',
            'tags' => 'Tags',
            'author' => 'Author',
            'date' => 'Date',
        ],
        $filter = null,
        $sort = null,
        $group = null
    ) {
        $this->id = $id;
        $this->class = $class;
        $this->features = $features;
        $this->order = $order;
        $this->viewModes = ($viewModes);
        $this->pageModes = ($pageModes);
        $this->filterBy = ($filterBy);
        $this->filterValue = ($filterValue);
        $this->sortBy = ($sortBy);
        $this->sortOrder = ($sortOrder);
        $this->groupBy = ($groupBy);
        $this->actions = ($actions);
        $this->exports = ($exports);
        $this->imports = ($imports);
        $this->data = $data;
        $this->viewMode = $viewMode;
        $this->pageMode = $pageMode;
        $this->filter = $filter;
        $this->sort = $sort;
        $this->group = $group;
        $this->searchCategory = $searchCategory;
        $this->searchCategories = $searchCategories;
    }

    public function handleDropdownSelect($event)
    {
        // dd($event);
        Log::debug($event);
        $dropdownId = $event['target'];
        $selectedValue = $event['detail']['value'];

        switch ($dropdownId) {
            case 'viewModeDropdown':
                $this->viewMode = $selectedValue;
                break;
            case 'pageModeDropdown':
                $this->pageMode = $selectedValue;
                break;
            case 'searchCategoryDropdown':
                $this->searchCategory = $selectedValue;
                break;
            case 'filterDropdown':
                $this->filter = $selectedValue;
                break;
            case 'sortByDropdown':
                $this->sort = $selectedValue;
                break;
            case 'sortOrderDropdown':
                $this->sortOrder = $selectedValue;
                break;
            case 'groupByDropdown':
                $this->group = $selectedValue;
                break;
            // ... handle other dropdown IDs
        }
        // Optionally emit an event upward
        $this->dispatch('toolbarUpdated', [
            'dropdownId' => $dropdownId,
            'selectedValue' => $selectedValue,
        ]);
    }

    public function handleDropdownChange($value)
    {
        // Handle dropdown value changes
        // You can use a switch statement to handle different dropdowns
        // For example, if you have a dropdown for view modes
        $this->viewMode = $value;
        $this->filter = $value;
        $this->sort = $value;
        $this->group = $value;
        // ... handle other dropdowns ...
        $this->searchCategory = $value;
        $this->sortOrder = $value;
        $this->pageMode = $value;
    }

    public function formatForDropdown(array $options) {
        return $options;
    }

    public function applyActions()
    {
        // Emit an event with the selected actions
        $this->dispatch('applyActions', $this->selectedActions);
    }

    public function resetFilters()
    {
        // Reset filter-related properties
        $this->filter = null; // Or your default filter value
        // ... Reset other filter properties ...

        // Emit an event to notify parent component
        $this->dispatch('filtersReset');
    }

    public function clearSearch()
    {
        $this->searchQuery = '';

        // Optionally emit an event to the parent component
        $this->dispatch('searchCleared');
    }

    public function render()
    {
        return view('livewire.toolbar');
    }
}

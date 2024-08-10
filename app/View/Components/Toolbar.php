<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toolbar extends Component
{
    public $id;
    public $class;
    public $features;
    public $order;
    public $viewModes;
    public $pageModes;
    public $filterBy;
    public $sortBy;
    public $sortOrder;
    public $groupBy;
    public $actions;
    public $exports;
    public $imports;
    public $data;
    /**
     * Create a new component instance.
     */
    public function __construct(
        $id = null,
        $class = null,
        $features = [
            'viewMode' => true,
            'pageMode' => true,
            'search' => true,
            'searchCategory' => true,
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
            ['search', 'searchCategory'],
            ['filter', 'filterValue'],
            ['sort'],
            ['group'],
            ['actions', 'export', 'import', 'settings'],
        ],
        $viewModes = [
            'card' => 'Card',
            'list' => 'List',
            'table' => 'Table',
        ],
        $pageModes = [
            'pagination' => 'Pagination',
            'infinite' => 'Infinite Scroll',
        ],
        $filterBy = [], // Leave filter options empty by default - populate dynamically
        $sortBy = [],    // Leave sort options empty by default - populate dynamically 
        $sortOrder = [
            'asc' => 'Ascending',
            'desc' => 'Descending',
        ],
        $groupBy = [],  // Leave group options empty by default - populate dynamically
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
        $data = []  // You can have a general $data property for any other data 
                   // needed by the component (e.g., selected values)
        ) {
        $this->id = $id;
        $this->class = $class;
        $this->features = $features;
        $this->order = $order;
        $this->viewModes = $viewModes;
        $this->pageModes = $pageModes;
        $this->filterBy = $filterBy;
        $this->sortBy = $sortBy;
        $this->sortOrder = $sortOrder;
        $this->groupBy = $groupBy;
        $this->actions = $actions;
        $this->exports = $exports;
        $this->imports = $imports;
        $this->data = $data;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.toolbar');
    }
}

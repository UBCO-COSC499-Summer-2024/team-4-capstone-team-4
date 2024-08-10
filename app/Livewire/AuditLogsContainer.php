<?php

namespace App\Livewire;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AuditLogsContainer extends Component {
    public $search = '';
    public $filters = [
        'Users' => [],
        'Actions' => [],
        'Schemas' => [],
        'Operations' => []
    ];

    public $selectedFilter = [
        'Users' => [],
        'Actions' => [],
        'Schemas' => [],
        'Operations' => []
    ];

    protected $listeners = [
        'change-search-query' => 'updateSearch',
        'clear-filters' => 'clearFilters',
        'change-filter' => 'updateFilter'
    ];

    /**
     * Initialize the component with default filter values.
     *
     * @return void
     */
    public function mount() {
        $this->populateFilters();
    }

    /**
     * Update the search query value.
     *
     * @param string $value The new search query value.
     * @return void
     */
    public function updateSearch($value) {
        $this->search = $value;
    }

    /**
     * Update the selected filters based on the provided category, item, and checked state.
     *
     * @param string $category The filter category (e.g., 'Users', 'Actions').
     * @param string $item The filter item to be added or removed.
     * @param bool $isChecked Indicates whether the item is checked or unchecked.
     * @return void
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
     * Clear all selected filters and reset them to default empty arrays.
     *
     * @return void
     */
    public function clearFilters() {
        $this->selectedFilter = [
            'Users' => [],
            'Actions' => [],
            'Schemas' => [],
            'Operations' => []
        ];
    }

    /**
     * Populate filter options with distinct values from the AuditLog model.
     *
     * @return void
     */
    public function populateFilters() {
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
        $this->filters['Users'] = $combinedUsers;
        $this->filters['Actions'] = AuditLog::select('action')->distinct()->get()->pluck('action');
        $this->filters['Schemas'] = AuditLog::select('table_name')->distinct()->get()->pluck('table_name');
        $this->filters['Operations'] = AuditLog::select('operation_type')->distinct()->get()->pluck('operation_type');
    }

    /**
     * Render the component view with filters and selected filter data.
     *
     * @return \Illuminate\View\View The view for the component.
     */
    public function render() {
        return view('livewire.audit-logs-container', [
            'filters' => $this->filters,
            'selectedFilter' => $this->selectedFilter,
        ]);
    }
}


<?php

namespace App\Livewire;

use App\Models\AuditLog;
use Livewire\Component;

class AuditLogsContainer extends Component {
    public $search = '';
    public $filters = [
        'Users' => [],
        'Actions' => [],
        'Schemas' => [],
        'Operations' => []
    ];
    public $selectedFilter = [];

    protected $listeners = [
        'change-search-query' => 'updateSearch',
        'clear-filters' => 'clearFilters'
    ];

    public function mount() {
        $this->populateFilters();
    }

    public function updateSearch($value) {
        $this->search = $value;
    }

    public function clearFilters() {
        $this->selectedFilter = [];
    }

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

    public function render() {
        return view('livewire.audit-logs-container');
    }
}

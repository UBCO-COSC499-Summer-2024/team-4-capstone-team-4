<?php

namespace App\Livewire;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogTable extends Component {
    use WithPagination;
    public $auditLogs;
    public $viewMode;
    public $perpage = 20;
    public $page = 1;
    public $selectAll = false;
    public $selectedItems = [];

    public function mount($viewMode = 'table', $perpage, $page) {
        $this->auditLogs = AuditLog::all();
        $this->viewMode = $viewMode;
        $this->perpage = $perpage;
        $this->page = $page;
    }

    public function render() {
        return view('livewire.audit-log-table', [
            'viewMode' => $this->viewMode,
            'auditLogs' => $this->auditLogs,
            'page' => $this->page,
            'perpage' => $this->perpage,
        ]);
    }
}

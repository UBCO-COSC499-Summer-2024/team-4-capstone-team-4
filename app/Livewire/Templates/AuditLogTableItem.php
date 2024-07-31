<?php

namespace App\Livewire\Templates;

use Livewire\Component;

class AuditLogTableItem extends Component
{
    public $auditLog;
    public $selected;

    public function mount($auditLog) {
        $this->auditLog = $auditLog;
    }

    public function render() {
        return view('livewire.templates.audit-log-table-item', [
            'auditLog' => $this->auditLog,
        ]);
    }

    public function delete($id) {
    }

    public function revert($id) {
    }
}

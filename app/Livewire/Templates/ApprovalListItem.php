<?php

namespace App\Livewire\Templates;

use App\Models\Approval;
use App\Models\ApprovalHistory;
use App\Models\ApprovalStatus;
use App\Models\ApprovalType;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Pluralizer;
use Livewire\Component;

class ApprovalListItem extends Component {
    public $approval;
    public $type = 'all'; // status
    public $isEditing = false;
    public $selected = false;
    public $headers;
    public $options = [
        'delete' => true,
        'approve' => true,
        'reject' => true,
        'cancel' => true,
        'edit' => true,
    ];

    public function mount($approval, $type, $headers, $options) {
        $this->approval = $approval;
        $this->type = $type;
        $this->headers = $headers;
        $this->options = $options;
    }

    public function render() {
        return view('livewire.templates.approval-list-item');
    }

    public function deleteApproval($id) {
        if (!$this->options['delete'] || !$this->approval->id === $id) {
            return;
        }
        $audit_user = User::find((int) Auth::id())->getName();
        try {
            $old_value = null;
            $item = null;
            switch($this->type) {
                case 'status':
                    $item = ApprovalStatus::find($id);
                    break;
                case 'type':
                    $item = ApprovalType::find($id);
                    break;
                case 'history':
                    $item = ApprovalHistory::find($id);
                    break;
                default:
                    $item = Approval::find($id);
                    break;
            }

            if ($item) {
                $old_value = $item->getAttributes();
                $item->delete();

                AuditLog::create([
                    'user_id' => Auth::id(),
                    'user_alt' => $audit_user,
                    'action' => 'delete',
                    'description' => 'Deleted '.($this->type === 'all') ? 'approval' : 'approval ' . $this->type.' with ID: '.$id,
                    'old_value' => json_encode($old_value),
                    'new_value' => null,
                    'operation_type' => 'DELETE',
                    'table_name' => ($this->type === 'all') ? 'approvals' : 'approval_'.Pluralizer::plural($this->type)
                ]);
            } else {
                $this->dispatch('show-toast', [
                    'type' => 'error',
                    'message' => 'Approval not found'
                ]);
            }
        } catch(\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Error deleting approval: '.$e->getMessage()
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'user_alt' => $audit_user,
                'action' => 'error',
                'description' => 'Error deleting '.($this->type === 'all') ? 'approval' : 'approval ' . $this->type.' with ID: '.$id,
                'operation_type' => 'DELETE',
                'table_name' => ($this->type === 'all') ? 'approvals' : 'approval_'.Pluralizer::plural($this->type)
            ]);
        }
    }

    public function approveApproval($id) {
        if (!$this->options['approve'] || !$this->approval->id === $id) {
            return;
        }
        $audit_user = User::find((int) Auth::id())->getName();
        try {
            $old_value = null;
            $item = null;
            switch($this->type) {
                case 'status':
                case 'type':
                case 'history':
                    $item = null;
                    break;
                default:
                    $item = Approval::find($id);
                    break;
            }

            if ($item) {
                $item->approve();
            }
        } catch(\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Error approving approval: '.$e->getMessage()
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'user_alt' => $audit_user,
                'action' => 'error',
                'description' => 'Error approving '.($this->type === 'all') ? 'approval' : 'approval ' . $this->type.' with ID: '.$id,
                'operation_type' => 'UPDATE',
                'table_name' => ($this->type === 'all') ? 'approvals' : 'approval_'.Pluralizer::plural($this->type)
            ]);
        }
    }

    public function rejectApproval($id) {
        if (!$this->options['reject'] || !$this->approval->id === $id) {
            return;
        }
        $audit_user = User::find((int) Auth::id())->getName();
        try {
            $old_value = null;
            $item = null;
            switch($this->type) {
                case 'status':
                case 'type':
                case 'history':
                    $item = null;
                    break;
                default:
                    $item = Approval::find($id);
                    break;
            }

            if ($item) {
                $item->reject();
            }
        } catch(\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Error rejecting approval: '.$e->getMessage()
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'user_alt' => $audit_user,
                'action' => 'error',
                'description' => 'Error rejecting '.($this->type === 'all') ? 'approval' : 'approval ' . $this->type.' with ID: '.$id,
                'operation_type' => 'UPDATE',
                'table_name' => ($this->type === 'all') ? 'approvals' : 'approval_'.Pluralizer::plural($this->type)
            ]);
        }
    }

    public function cancelApproval($id) {
        if (!$this->options['cancel'] || !$this->approval->id === $id) {
            return;
        }
        $audit_user = User::find((int) Auth::id())->getName();
        try {
            $old_value = null;
            $item = null;
            switch($this->type) {
                case 'status':
                case 'type':
                case 'history':
                    $item = null;
                    break;
                default:
                    $item = Approval::find($id);
                    break;
            }

            if ($item) {
                $item->cancel();
            }
        } catch(\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Error cancelling approval: '.$e->getMessage()
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'user_alt' => $audit_user,
                'action' => 'error',
                'description' => 'Error cancelling '.($this->type === 'all') ? 'approval' : 'approval ' . $this->type.' with ID: '.$id,
                'operation_type' => 'UPDATE',
                'table_name' => ($this->type === 'all') ? 'approvals' : 'approval_'.Pluralizer::plural($this->type)
            ]);
        }
    }

    public function editApproval($id) {
        if ($this->approval->id === $id) {
            $this->isEditing = true;
            $this->selected = true;
        }
    }

    public function cancelEdit($id) {
        if ($this->approval->id === $id) {
            $this->isEditing = false;
            $this->selected = false;
        }
    }

    public function saveApproval($id) {
        if (!$this->isEditing || !$this->approval->id === $id) {
            return;
        }
        $audit_user = User::find((int) Auth::id())->getName();
        try {
            $old_value = null;
            $item = null;
            switch($this->type) {
                case 'status':
                    $item = ApprovalStatus::find($id);
                    break;
                case 'type':
                    $item = ApprovalType::find($id);
                    break;
                case 'history':
                    $item = ApprovalHistory::find($id);
                    break;
                default:
                    $item = Approval::find($id);
                    break;
            }

            if ($item) {
                $old_value = $item->getAttributes();
                $item->save();
                $this->isEditing = false;
                $this->selected = false;

                AuditLog::create([
                    'user_id' => Auth::id(),
                    'user_alt' => $audit_user,
                    'action' => 'update',
                    'description' => 'Updated '.($this->type === 'all') ? 'approval' : 'approval ' . $this->type.' with ID: '.$id,
                    'old_value' => json_encode($old_value),
                    'new_value' => json_encode($item->getAttributes()),
                    'operation_type' => 'UPDATE',
                    'table_name' => ($this->type === 'all') ? 'approvals' : 'approval_'.Pluralizer::plural($this->type)
                ]);
            } else {
                $this->dispatch('show-toast', [
                    'type' => 'error',
                    'message' => 'Approval not found'
                ]);
            }
        } catch(\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Error saving approval: '.$e->getMessage()
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'user_alt' => $audit_user,
                'action' => 'error',
                'description' => 'Error saving '.($this->type === 'all') ? 'approval' : 'approval ' . $this->type.' with ID: '.$id,
                'operation_type' => 'UPDATE',
                'table_name' => ($this->type === 'all') ? 'approvals' : 'approval_'.Pluralizer::plural($this->type)
            ]);
        }
    }
}

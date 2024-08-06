<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Event\Telemetry\System;

class Approval extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'approvals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'approval_type_id', 'status_id', 'approved_at', 'rejected_at', 'details', 'approved_by', 'active', 'rejected_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user associated with the approval.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the approval type associated with the approval.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvalType() {
        return $this->belongsTo(ApprovalType::class);
    }

    /**
     * Get the status associated with the approval.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status() {
        return $this->belongsTo(ApprovalStatus::class);
    }

    /**
     * Get the user associated with the approval.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvedBy() {
        return $this->belongsTo(UserRole::class, 'approved_by');
    }

    /**
     * Get the user associated with the approval.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rejectedBy() {
        return $this->belongsTo(UserRole::class, 'rejected_by');
    }

    /**
     * Get the approval details.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories() {
        return $this->hasMany(ApprovalHistory::class);
    }

    /**
     * Check the status of the approval.
     *
     * @return mixed
     */
    public function checkStatus() {
        return $this->status->id;
    }


    public function requiredApprovals() {
        return $this->approvalType->approvals_required;
    }

    public function isApproved() {
        return $this->status_id == ApprovalStatus::where('name', 'approved')->first()->id;
    }

    public function isRejected() {
        return $this->status_id == ApprovalStatus::where('name', 'rejected')->first()->id;
    }

    public function isIntermediate() {
        return $this->status_id == ApprovalStatus::where('name', 'intermediate')->first()->id;
    }

    public function isPending() {
        return $this->status_id == ApprovalStatus::where('name', 'pending')->first()->id;
    }

    public function isCancelled() {
        return $this->status_id == ApprovalStatus::where('name', 'cancelled')->first()->id;
    }

    public function isActive() {
        return $this->active;
    }

    public function approve() {
        $approvedStatus = ApprovalStatus::where('name', 'approved')->first();
        $intermediateStatus = ApprovalStatus::where('name', 'intermediate')->first();

        $this->status_id = $this->approvedCount() + 1 >= $this->approvalType->approvals_required ? $approvedStatus->id : $intermediateStatus->id;
        $this->save();

        $this->logHistory($this->status_id, 'Approved');

        if ($this->status_id == $approvedStatus->id) {
            $this->finalizeApproval();
        }
    }

    public function reject() {
        $rejectedStatus = ApprovalStatus::where('name', 'rejected')->first();
        $this->status_id = $rejectedStatus->id;
        $this->save();

        $this->rejected_at = now();
        $this->rejected_by = Auth::id();
        $this->active = false;
        $this->save();

        $this->logHistory($this->status_id, 'Rejected');
    }

    public function cancel() {
        $this->status_id = ApprovalStatus::where('name', 'cancelled')->first()->id;
        $this->active = false;
        $this->save();

        $this->logHistory($this->status_id, 'Cancelled');
    }

    public function finalizeApproval() {
        $this->status_id = ApprovalStatus::where('name', 'approved')->first()->id;
        $this->approved_at = now();
        $this->approved_by = Auth::id();
        $this->active = false;
        $this->save();

        $this->logHistory($this->status_id, 'Finalized');
    }

    public function approvedCount() {
        $approvalTypeId = $this->approval_type_id;

        $approvedCount = Approval::where('approval_type_id', $approvalTypeId)
                                ->where('status_id', ApprovalStatus::where('name', 'approved')->first()->id)
                                ->count();

        $requiredApprovals = $this->approvalType->approvals_required;

        if ($approvedCount < $requiredApprovals) {
            $intermediateCount = Approval::where('approval_type_id', $approvalTypeId)
                                        ->where('status_id', ApprovalStatus::where('name', 'intermediate')->first()->id)
                                        ->count();

            return $approvedCount + $intermediateCount;
        }

        return $approvedCount;
    }

    public static function getColumns() {
        $self = new Self;
        return $self->getConnection()->getSchemaBuilder()->getColumnListing($self->getTable());
    }

    protected function logHistory($statusId, $remarks) {
        $history = new ApprovalHistory();
        $history->approval_id = $this->id;
        $history->status_id = $statusId;
        $history->user_id = Auth::id();
        $history->remarks = $remarks;
        $history->changed_at = now();
        $history->save();

        $audit_user = User::findOrFail(auth()->user()->id)->getName();
        AuditLog::create([
            'user_id' => auth()->user()->id,
            'user_alt' => $audit_user,
            'action' => strtolower($remarks) . ' approval',
            'description' => "$audit_user $remarks approval with ID: " . $this->id,
            'old_value' => json_encode($this->getOriginal()),
            'new_value' => json_encode($this->getAttributes()),
            'operation_type' => 'UPDATE',
            'table_name' => 'approvals'
        ]);
    }

    public static function audit($action, $details, $description) {
        $id = Auth::user()->id ?? null;
        $audit_user = null;
        if ($id) {
            $audit_user = User::find($id);
        }

        AuditLog::create([
            'user_id' => $id ?? null,
            'user_alt' => $audit_user ?? 'System',
            'action' => $action,
            'description' => $description,
            'old_value' => $details['old_value'] ?? null,
            'new_value' => $details['new_value'] ?? null,
            'operation_type' => $details['operation_type'] ?? 'UPDATE',
            'table_name' => 'approvals'
        ]);
    }

    public function log_audit($action, $details, $description) {
        self::audit($action, $details, $description);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    // check status of approval and also how many approvals required etc
    public function checkStatus() {
        $approvalType = $this->approvalType;
        $histories = $this->histories;
        $status = $this->status;
        $approvedStatus = ApprovalStatus::where('name', 'approved')->first();
        $rejectedStatus = ApprovalStatus::where('name', 'rejected')->first();
        $intermediateStatus = ApprovalStatus::where('name', 'intermediate')->first();
        $pendingStatus = ApprovalStatus::where('name', 'pending')->first();
        $approvalsRequired = $approvalType->approvals_required;
        $approvedCount = $histories->where('status_id', $approvedStatus->id)->count() ?? 0;
        $rejectedCount = $histories->where('status_id', $rejectedStatus->id)->count() ?? 0;
        $intermediateCount = $histories->where('status_id', $intermediateStatus->id)->count() ?? 0;
        $approvalStatus = $pendingStatus->id;
        if ($approvedCount >= $approvalsRequired) {
            $approvalStatus = $approvedStatus->id;
        } else if ($rejectedCount > 0) {
            $approvalStatus = $rejectedStatus->id;
        } else if ($intermediateCount > 0) {
            $approvalStatus = $intermediateStatus->id;
        }
        if ($status->id != $approvalStatus) {
            $this->status_id = $approvalStatus;
            $this->save();
        }
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

        $history = new ApprovalHistory();
        $history->approval_id = $this->id;
        $history->status_id = $this->status_id;
        $history->user_id = Auth::id();
        $history->remarks = 'Approved';
        $history->changed_at = now();
        $history->save();

        if ($this->status_id == $approvedStatus->id) {
            $this->finalizeApproval();
        }
    }

    public function reject() {
        $rejectedStatus = ApprovalStatus::where('name', 'rejected')->first();
        $this->status_id = $rejectedStatus->id;
        $this->save();

        $history = new ApprovalHistory();
        $history->approval_id = $this->id;
        $history->status_id = $this->status_id;
        $history->user_id = Auth::id();
        $history->remarks = 'Rejected';
        $history->changed_at = now();
        $history->save();

        $this->rejected_at = now();
        $this->rejected_by = Auth::id();
        $this->active = false;
        $this->save();
    }

    public function cancel() {
        $this->status_id = ApprovalStatus::where('name', 'cancelled')->first()->id;
        $this->active = false;
        $this->save();

        $history = new ApprovalHistory();
        $history->approval_id = $this->id;
        $history->status_id = $this->status_id;
        $history->user_id = Auth::id();
        $history->remarks = 'Cancelled';
        $history->changed_at = now();
        $history->save();
    }

    public function finalizeApproval() {
        $this->status_id = ApprovalStatus::where('name', 'approved')->first()->id;
        $this->approved_at = now();
        $this->approved_by = Auth::id();
        $this->active = false;
        $this->save();
    }

    public function approvedCount() {
        return $this->histories->where('status_id', ApprovalStatus::where('name', 'approved')->first()->id)->orWhere('status_id', ApprovalStatus::where('name', 'intermediate')->first()->id)->count() ?? 0;
    }
}

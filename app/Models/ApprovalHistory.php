<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'approval_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'approval_id', 'status_id', 'user_id', 'remarks', 'changed_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'changed_at' => 'datetime',
    ];

    /**
     * Get the user associated with the approval history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the status associated with the approval history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status() {
        return $this->belongsTo(ApprovalStatus::class);
    }

    /**
     * Get the approval associated with the approval history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approval() {
        return $this->belongsTo(Approval::class);
    }

    public static function getColumns() {
        $self = new Self;
        return $self->getConnection()->getSchemaBuilder()->getColumnListing($self->getTable());
    }
}

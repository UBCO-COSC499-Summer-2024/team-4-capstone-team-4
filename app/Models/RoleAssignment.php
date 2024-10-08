<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InstructorPerformance;
use App\Models\ServiceRole;
use App\Models\UserRole;

class RoleAssignment extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_assignments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_role_id', 'assigner_id', 'instructor_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // public static function boot() {
    //     parent::boot();

    //     static::created(function ($assignment) {
    //         $serviceRole = $assignment->serviceRole;
    //         $instructorPerformance = InstructorPerformance::firstOrCreate(
    //             ['instructor_id' => $assignment->instructor_id, 'year' => $serviceRole->year],
    //             ['total_hours' => 0, 'sei_avg' => 0]
    //         );

    //         $instructorPerformance->total_hours += array_sum($serviceRole->monthly_hours);
    //         $instructorPerformance->save();
    //     });
    // }

    /**
     * Get the service role associated with the assignment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role() {
        return $this->belongsTo(ServiceRole::class);
    }

    /**
     * Get the user role who assigned the role (dept_head or dept_staff).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assigner()
    {
        return $this->belongsTo(UserRole::class, 'assigner_id')->whereIn('role', ['dept_head', 'dept_staff']);
    }

    /**
     * Get the user role who received the role assignment (instructor).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instructor()
    {
        return $this->belongsTo(UserRole::class, 'instructor_id')->where('role', 'instructor');
    }

    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'instructor_id');
    }

    public function extraHours()
    {
        return $this->hasManyThrough(ExtraHour::class, UserRole::class, 'id', 'instructor_id', 'instructor_id', 'id');
    }

    public static function audit($action, $details = [], $description) {
        $audit_user = User::find((int) auth()->user()->id)->getName();
        AuditLog::create([
            'user_id' => (int) auth()->user()->id,
            'user_alt' => $audit_user ?? 'System',
            'action' => $action,
            'table_name' => 'role_assignments',
            'operation_type' => $details['operation_type'] ?? 'UPDATE',
            'old_value' => $details['old_value'] ?? null,
            'new_value' => $details['new_value'] ?? null,
            'description' => $description,
        ]);
    }

    public function log_audit($action, $details = [], $description) {
        self::audit($action, $details, $description);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserRole;

class ExtraHour extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'extra_hours';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'hours', 'year', 'month',  'assigner_id', 'instructor_id', 'area_id', 'room',
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

    /**
     * Get the dept_head or dept_staff user role who assigned the extra hours.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assigner() {
        return $this->belongsTo(UserRole::class, 'assigner_id')->where('role', 'dept_head')
                                                                 ->orWhere('role', 'dept_staff');
    }

    /**
     * Get the instructor user role associated with the extra hours.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instructor() {
        return $this->belongsTo(UserRole::class, 'instructor_id')->where('role', 'instructor');
    }

    /**
     * Get the area associated with the extra hours.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area() {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public static function audit($action, $details = [], $description) {
        $audit_user = User::find((int) auth()->user()->id)->getName();
        AuditLog::create([
            'user_id' => (int) auth()->user()->id,
            'user_alt' => $audit_user ?? 'System',
            'action' => $action,
            'table_name' => 'extra_hours',
            'operation_type' => $details['operation_type'] ?? 'UPDATE',
            'old_value' => $details['old_value'] ?? null,
            'new_value' => $details['new_value'] ?? null,
            'description' => $description,
        ]);
    }

    public function log_audit($action, $details = [], $description) {
        self::audit($action, $details, $description);
    }

    public function getRoom() {
        $room = $this->room;
        $building = null;
        $room_number = null;
        $suffix = null;
        if ($room) {
            // explode either by space or hyphen or underscore
            $parts = preg_split('/[\s-_]/', $room);
            $building = $parts[0];
            $room_number = $parts[1];
            $suffix = $parts[2] ?? null;
        }
        return [
            'room' => $room,
            'building' => $building,
            'room_number' => $room_number,
            'suffix' => $suffix,
        ];
    }
}

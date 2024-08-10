<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseSection;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;

class Teach extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'teaches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_section_id', 'instructor_id',
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
     * Get the course section that is being taught.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courseSection() {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    /**
     * Get the instructor (user role of type 'instructor') that is teaching the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instructor()
    {
        return $this->belongsTo(UserRole::class, 'instructor_id')
                    ->where('role', 'instructor');
    }

    // other functions

    public static function getInstructorsForCourses() {
        $teaches = self::all();

        $courseInstructors = [];

        foreach ($teaches as $teach) {
            if (!isset($courseInstructors[$teach->course_section_id])) {
                $courseInstructors[$teach->course_section_id] = [];
            }
            $courseInstructors[$teach->course_section_id][] = $teach->user_role_id;
        }

        return $courseInstructors;
    }

    public static function audit($action, $details, $description) {
        $audit_user = User::find((int) Auth::id())->getName();
        AuditLog::create([
            'user_id' => (int) Auth::id(),
            'user_alt' => $audit_user ?? 'System',
            'action' => $action,
            'table_name' => 'course_sections',
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

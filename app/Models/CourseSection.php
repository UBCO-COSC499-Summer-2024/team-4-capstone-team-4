<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TeachingAssistant;
use App\Models\SeiData;
use App\Models\Teach;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;

class CourseSection extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'course_sections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prefix', 'number', 'area_id', 'enroll_start', 'enroll_end', 'dropped', 'capacity', 'year', 'term', 'session', 'section', 'room', 'time_start', 'time_end'
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
     * Get the area associated with the course section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area() {
        return $this->belongsTo(Area::class, 'area_id');
    }

    /**
     * Get the SEI data associated with the course section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function seiData() {
        return $this->hasOne(SeiData::class);
    }

    /**
     * Get the teaching assistants assigned to the course section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teachingAssistants() {
        return $this->belongsToMany(TeachingAssistant::class, 'assists', 'course_section_id', 'ta_id')
                    ->withPivot('rating');
    }

    /**
     * Get the instructors teaching this course section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teaches() {
        return $this->hasOne(Teach::class, 'course_section_id');
    }

    public static function calculateDropped($enroll_start, $enroll_end) {
        $dropped = 0;

        if($enroll_start > $enroll_end) {
            $dropped = $enroll_start - $enroll_end;
        } else {
            $dropped = 0;
        }

        return $dropped;
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

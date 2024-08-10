<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseSection;
use App\Models\TeachingAssistant;
use Illuminate\Support\Facades\Auth;


class Assist extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_section_id', 'ta_id', 'rating',
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
     * Get the course section associated with the assist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courseSection() {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    /**
     * Get the teaching assistant associated with the assist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teachingAssistant() {
        return $this->belongsTo(TeachingAssistant::class, 'ta_id');
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

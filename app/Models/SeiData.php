<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseSection;
use Illuminate\Support\Facades\Auth;

class SeiData extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sei_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    //  ------ old -------

    // protected $fillable = [
    //     'cid', 'q1im', 'q2im', 'q3im', 'q4im', 'q5im', 'q6im',
    // ];

    //  ------ new -------

    protected $fillable = [
        'course_section_id', 'questions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'questions' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the course section associated with the SEI data.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courseSection() {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public static function calculateSEIAverage($courseSectionId) {
        $data = self::where('course_section_id', $courseSectionId)->first();
        if($data){
            $questions = json_decode($data->questions, true);
            $averageScore = array_sum($questions) / count($questions);
    
            return round($averageScore, 1); 
        }
        
        return null;
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

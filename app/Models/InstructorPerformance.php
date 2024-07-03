<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserRole;

class InstructorPerformance extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'instructor_performance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'score', 'total_hours', 'target_hours', 'sei_avg', 'year', 'instructor_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'year' => 'date:Y',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the instructor associated with the performance data.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instructor()
    {
        return $this->belongsTo(UserRole::class, 'instructor_id')->where('role', 'instructor');
    }

    // other functions

    public static function updatePerformance() {
        $seiAverages = SeiData::calculateSEIAverages();
        $courseInstructors = Teach::getInstructorsForCourses();
    
        $instructorScores = [];
        $courseCounts = [];
    
        foreach ($seiAverages as $courseSectionId => $averageScore) {
            if (isset($courseInstructors[$courseSectionId])) {
                foreach ($courseInstructors[$courseSectionId] as $userRoleId) {
                    if (!isset($instructorScores[$userRoleId])) {
                        $instructorScores[$userRoleId] = 0;
                        $courseCounts[$userRoleId] = 0;
                    }
                    $instructorScores[$userRoleId] += $averageScore;
                    $courseCounts[$userRoleId] += 1;
                }
            }
        }
    
        foreach ($instructorScores as $userRoleId => $totalScore) {
            $courseCount = $courseCounts[$userRoleId];
            $averageScore = $totalScore / $courseCount;
            $roundedScore = round($averageScore, 1); // Round to one decimal place
    
            // self::updateOrCreate(
            //     ['instructor_id' => $userRoleId],
            //     ['sei_avg' => $roundedScore]
            // );
    
            // Uncomment for debugging:
            // echo 'userrole ', $userRoleId, ' ', 'score', $roundedScore;
        }


}
}

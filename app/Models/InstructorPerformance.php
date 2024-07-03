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
        'score', 'total_hours', 'target_hours', 'sei_avg', 'enrolled_avg', 'dropped_avg', 'capacity_avg', 'year', 'instructor_id',
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

    public static function updatePerformance($instructor_id, $year) {
        $seiAverages = SeiData::calculateSEIAverages();
        $courses = Teach::where('instructor_id', $instructor_id)
        ->whereHas('courseSection', function ($query) use ($year) {
            $query->where('year', $year);
        })->pluck('course_section_id');
                
        if (count($courses) === 0) {
            echo "No courses found for instructor ID: $instructor_id";
            return;
        }

        $sei_sum = 0;
        $count = 0;
        foreach ($courses as $course_id) {
            if (isset($seiAverages[$course_id])) {
                //echo $course_id . ":" . $seiAverages[$course_id] . "\n";
                $sei_sum += $seiAverages[$course_id];
                $count ++;
            }
        }
        //echo $sei_sum  . "\n" ;
        //echo count($courses)  . "\n" ;
        $sei_avg = 0;
        if($count > 0){
            $sei_avg = $sei_sum / $count;
        }

        echo $instructor_id . " - " .  $sei_avg ;
       /*  $performance = InstructorPerformance::where('instructor_id', $instructor_id)->where('year', $year)->first();
        if ($performance!= null) {
            $performance->update([
                'sei_avg' => $sei_avg
            ]);
        } */

    }
}

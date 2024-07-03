<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseSection;

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



    // other functions

    public static function calculateSEIAverages() {
        $seiData = self::all();

        $seiAverages = [];

        foreach ($seiData as $data) {
            $questions = json_decode($data->questions, true);
            $averageScore = array_sum($questions) / count($questions);
            $seiAverages[$data->course_section_id] = $averageScore;
        }

        return $seiAverages;
    }

    public static function calculateSEIAreaAverages() {
        $seiAverages = self::calculateSEIAverages();
        $coursesByArea = CourseSection::getCoursesByArea();
        
        $areaAverages = [];
        
        foreach ($coursesByArea as $areaId => $courses) {
            $totalScore = 0;
            $courseCount = 0;
            
            foreach ($courses as $course) {
                if (isset($seiAverages[$course->id])) {
                    $totalScore += $seiAverages[$course->id];
                    $courseCount++;
                }
            }
            
            if ($courseCount > 0) {
                $areaAverages[$areaId] = $totalScore / $courseCount;
            } else {
                $areaAverages[$areaId] = 0;
            }
        }
        
        return $areaAverages;
    }
}

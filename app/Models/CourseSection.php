<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TeachingAssistant;
use App\Models\SeiData;
use App\Models\Teach;
use App\Models\Area;

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
        'name', 'area_id', 'enrolled', 'dropped', 'capacity', 'year', 'term', 'session', 'section'
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teaches() {
        return $this->hasMany(Teach::class, 'course_section_id');
    }

    // other functions

    public static function getCoursesByArea($year) {
        return self::with('area')->where('year', $year)->get()->groupBy('area_id');
    }

    public static function calculateEnrolledAverages() {
        // $courseSectionData = self::all();

        // $enrolledAvg = [];

        // foreach ($courseSectionData as $data) {
        //     $questions = json_decode($data->questions, true);
        //     $averageScore = array_sum($questions) / count($questions);
        //     $seiAverages[$data->course_section_id] = $averageScore;
        // }

        // return $seiAverages;
    }

    public static function calculateAreaEnrolledAverages($year) {
        // $data = self::where('year', $year)
        //     ->select('enrolled', 'capacity')
        //     ->get();

        // $totalEnrolled = $data->sum('enrolled');
        // $totalCapacity = $data->sum('capacity');

        // $averageEnrolled = $totalCapacity > 0 ? $totalEnrolled / $totalCapacity : 0;

        // $percentEnrolled = round($averageEnrolled * 100, 1);

        // return $percentEnrolled;

        // $areaAverages = self::calculateSEIAreaAverages($year);
        // $areasByDepartment = Area::getAreasByDepartment();
    
        // $departmentAverages = [];
        
        // foreach ($areasByDepartment as $departmentId => $areas) {
        //     $totalScore = 0;
        //     $areaCount = 0;
    
        //     foreach ($areas as $area) {
        //         if (isset($areaAverages[$area->id])) {
        //             $totalScore += $areaAverages[$area->id];
        //             $areaCount++;
        //         }
        //     }
            
        //     if ($areaCount > 0) {
        //         $departmentAverages[$departmentId] = $totalScore / $areaCount;
        //     } else {
        //         $departmentAverages[$departmentId] = 0;
        //     }
        // }

        // return $departmentAverages;
    }

    public static function calculateAreaDroppedAverages($year) {
    //     $data = self::where('year', $year)
    //         ->select('dropped', 'capacity')
    //         ->get();

    //     $totalDropped = $data->sum('dropped');
    //     $totalCapacity = $data->sum('capacity');

    //     $averageDropped = $totalCapacity > 0 ? $totalDropped / $totalCapacity : 0;

    //     $percentDropped = round($averageDropped * 100, 1);

    //     return $percentDropped;
    // }

    }
}

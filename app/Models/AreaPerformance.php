<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Area;

class AreaPerformance extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'area_performance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total_hours', 'sei_avg', 'enrolled_avg', 'dropped_avg', 'year', 'area_id',
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
     * Get the area associated with the performance data.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area() {
        return $this->belongsTo(Area::class, 'area_id');
    }

    //other functions

    // public static function updateAreaPerformance($year) {
    //     $areaAverages = SeiData::calculateSEIAreaAverages($year);
        
    //     foreach ($areaAverages as $areaId => $averageScore) {
    //         $roundedScore = round($averageScore, 1); 
            
    //         AreaPerformance::updateOrCreate(
    //             ['area_id' => $areaId],
    //             ['sei_avg' => $roundedScore]
    //         );

    //         echo 'area ', $areaId, ' ' , 'score ', $roundedScore;
    //     }

        
    // 

    public static function updateAreaPerformance($area_id, $year) {    
        $courses = CourseSection::where("area_id", $area_id)->where("year", $year)->pluck("id");

        $courseCount = 0;
        $totalSumAverageScore = 0;

        foreach($courses as $course => $course_id) {

                $sei_data = SeiData::where('course_section_id', $course_id)->get();

                if(!$sei_data->isEmpty()) {
                    $courseCount++;
                }
                foreach($sei_data as $data) {
                    $questionArray = json_decode($data->questions, true);
                    $averageScore = array_sum($questionArray) / count($questionArray);
                    $totalSumAverageScore += $averageScore;
                }
            }

        if ($courseCount != 0) {
            $totalRoundedAvg = round($totalSumAverageScore/$courseCount, 1);
            $performance = self::where('area_id', $area_id)->where('year', $year)->first();
            if ($performance != null) {
                $performance->update([
                    'sei_avg' => $totalRoundedAvg,
                ]);
            }
        }

        return;
    }

    public function addHours($month, $hour) {
        $totalHours = json_decode($this->total_hours, true);
        $totalHours[$month] += $hour;
        $this->total_hours = json_encode($totalHours);
        $this->save();
    }
}
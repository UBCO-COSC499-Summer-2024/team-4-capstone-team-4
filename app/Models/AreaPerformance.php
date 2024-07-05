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

    public static function updateAreaPerformance($year) {
        $areaAverages = SeiData::calculateSEIAreaAverages($year);
        // $areaEnrolledAverages = CourseSection::calculateAreaEnrolledAvg($year);
        // $areaDroppedAverages = CourseSection::calculateAreaDroppedAvg($year);

        foreach($areaAverages as $areaId => $averageScore){
            $roundedScore = round($averageScore, 1);
            $performance = self::where('area_id', $areaId)->where('year', $year)->first();
            if($performance != null){
                $performance->update(['sei_avg'=> $roundedScore]);
            }else{
                self::create([
                    'area_id'=>$areaId,
                    'year'=> $year,
                    'sei_avg'=> $roundedScore,
                    'enrolled_avg'=> 0,
                    'dropped_avg'=> 0,
                    'capacity_avg'=> 0,
                    'total_hours' => json_encode([
                        'January' => 0,
                        'February' => 0,
                        'March' => 0,
                        'April' => 0,
                        'May' => 0,
                        'June' => 0,
                        'July' => 0,
                        'August' => 0,
                        'September' => 0,
                        'October' => 0,
                        'November' => 0,
                        'December' => 0,
                    ]),
                ]);
            }
           
        }
        
        // $performance = self::where('year', $year)->get();
        // if($performance != null){
        //     $performance->update(['enrolled_avg'=> $areaEnrolledAverages]);
        //     $performance->update(['dropped_avg'=> $areaDroppedAverages]);
        // }else{
        //     self::create([
        //         'area_id'=>$areaId,
        //         'year'=> $year,
        //         'sei_avg'=> 0,
        //         'enrolled_avg'=> $areaEnrolledAverages,
        //         'dropped_avg'=> $areaDroppedAverages,
        //         'capacity_avg'=> 0,
        //         'total_hours' => json_encode([
        //             'January' => 0,
        //             'February' => 0,
        //             'March' => 0,
        //             'April' => 0,
        //             'May' => 0,
        //             'June' => 0,
        //             'July' => 0,
        //             'August' => 0,
        //             'September' => 0,
        //             'October' => 0,
        //             'November' => 0,
        //             'December' => 0,
        //         ]),
        //     ]);
        // }

        return;
    }

    public function addHours($month, $hour) {
        $totalHours = json_decode($this->total_hours, true);
        $totalHours[$month] += $hour;
        $this->total_hours = json_encode($totalHours);
        $this->save();
    }
}
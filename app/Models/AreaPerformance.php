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
        'score', 'total_hours', 'target_hours', 'sei_avg', 'year', 'area_id',
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

    public static function updateAreaPerformance() {
        $areaAverages = SeiData::calculateSEIAreaAverages();
        
        foreach ($areaAverages as $areaId => $averageScore) {
            $roundedScore = round($averageScore, 1); // Round to one decimal place
            
            // AreaPerformance::updateOrCreate(
            //     ['area_id' => $areaId],
            //     ['sei_avg' => $roundedScore]
            // );

            // echo 'area ', $areaId, ' ' , 'score ', $roundedScore;
        }
    }
}

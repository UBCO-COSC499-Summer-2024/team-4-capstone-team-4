<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class DepartmentPerformance extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'department_performance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'score', 'total_hours', 'target_hours', 'sei_avg', 'year', 'dept_id', 'enrolled_avg', 'dropped_avg', 'capacity_avg'
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
     * Get the department associated with the performance data.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department() {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    //other functions

    public static function updateDepartmentPerformance($year) {
        $departmentAverages = SeiData::calculateSEIDepartmentAverages($year);

        
        foreach($departmentAverages as $deptId => $averageScore){
            $performance = self::where('dept_id', $deptId)->where('year', $year)->first();
            if($performance != null){
                $performance->update(['sei_avg'=> $averageScore]);
            }else{
                self::create([
                    'dept_id'=>$deptId,
                    'year'=> $year,
                    'sei_avg'=> $averageScore,
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

        return;
    }
    
}

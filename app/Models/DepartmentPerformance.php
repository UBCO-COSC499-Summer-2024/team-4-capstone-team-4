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
        'total_hours', 'sei_avg', 'enrolled_avg', 'dropped_avg', 'year', 'dept_id',
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
     * Get the department associated with the performance data.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department() {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    //other functions

    public static function updateDepartmentSEIAvg($dept_id, $year) {
        $courses = CourseSection::whereHas('area', function ($query) use ($dept_id) {
            $query->where('dept_id', $dept_id);
            })
            ->where('year', $year)
            ->pluck('id');
    
            $courseCount = 0;
            $totalSumAverageScore = 0;
    
            foreach($courses as $course => $course_id) {
    
                $sei_data = SeiData::where('course_section_id', $course_id)->get();
    
                if(!$sei_data->isEmpty()) {
                    $courseCount++;
                }
                foreach($sei_data as $data) {
                    $questionArray = json_decode($data->questions, true);
                    $test[] = $questionArray;
                    $averageScore = array_sum($questionArray) / count($questionArray);
                    $totalSumAverageScore += $averageScore;
                }
            }
    
            if ($courseCount != 0) {
                $totalRoundedAvg = round($totalSumAverageScore/$courseCount, 1);
                $performance = self::where('dept_id', $dept_id)->where('year', $year)->first();
                if ($performance != null) {
                    $performance->update([
                        'sei_avg' => $totalRoundedAvg,
                    ]);
                }
            }
    
            return;
    }

    public static function updateDepartmentEnrollAndDropAvg($dept_id, $year) {
        $courseCount = 0;
        $totalSumEnrolledAvg = 0;
        $totalSumDroppedAvg = 0;

        $courses = CourseSection::whereHas('area', function ($query) use ($dept_id) {
            $query->where('dept_id', $dept_id);
            })
            ->where('year', $year)
            ->get();

        foreach($courses as $course) {
  
                $enrolled = $course->enrolled;
                $dropped = $course->dropped;
                $capacity = $course->capacity;

                $enrolledAvg = $enrolled / $capacity;
                $droppedAvg = $dropped / $capacity;

                $totalSumEnrolledAvg += $enrolledAvg;
                $totalSumDroppedAvg += $droppedAvg;

                $courseCount++;
        }

        if($courseCount != 0) {
            $totalEnrolledAvg = $totalSumEnrolledAvg / $courseCount;
            $totalDroppedAvg = $totalSumDroppedAvg / $courseCount;

            $totalEnrolledPercent = $totalEnrolledAvg * 100;
            $totalDroppedPercent = $totalDroppedAvg * 100;
    
            if(!is_int($totalEnrolledPercent)) {
                $totalEnrolledPercent = round($totalEnrolledPercent, 1);
            };
            if(!is_int($totalDroppedPercent)) {
                $totalDroppedPercent = round($totalDroppedPercent, 1);
            };  
    
            $performance = self::where('dept_id', $dept_id)->where('year', $year)->first();
            if ($performance != null) {
                $performance->update([
                    'enrolled_avg' => $totalEnrolledPercent,
                    'dropped_avg' => $totalDroppedPercent,
                ]);
            } 
        }

        return;
    }

    public static function updateDepartmentPerformance($dept_id, $year) {
        self::updateDepartmentSEIAvg($dept_id, $year);
        self::updateDepartmentEnrollAndDropAvg($dept_id, $year);
    }   

    public function addHours($month, $hour) {
        $totalHours = json_decode($this->total_hours, true);
        $totalHours[$month] += $hour;
        $this->total_hours = json_encode($totalHours);
        $this->save();
    }

}

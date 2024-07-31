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

    public static function updateAreaSEIAvg($area_id, $year) {
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

    public static function updateAreaEnrollAndDropAvg($area_id, $year) {
        $courseCount = 0;
        $totalSumEnrolledAvg = 0;
        $totalSumDroppedAvg = 0;

        $courses = CourseSection::where("area_id", $area_id)->where("year", $year)->get();

        foreach($courses as $course) {

                $enrolled = $course->enroll_end;
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

            $performance = self::where('area_id', $area_id)->where('year', $year)->first();
            if ($performance != null) {
                $performance->update([
                    'enrolled_avg' => $totalEnrolledPercent,
                    'dropped_avg' => $totalDroppedPercent,
                ]);
            }
        }

        return;
    }

    public static function updateAreaPerformance($area_id, $year) {
        self::updateAreaSEIAvg($area_id, $year);
        self::updateAreaEnrollAndDropAvg($area_id, $year);
    }

    public function addHours($month, $hour) {
        try {
            $totalHours = json_decode($this->total_hours, true);
            if (is_numeric($month)) {
                $month = date('F', mktime(0, 0, 0, $month, 1));
            }
            $totalHours[$month] += $hour;
            $this->total_hours = json_encode($totalHours);
            $this->save();
            AuditLog::create([
                'user_id' => (int) auth()->user()->id,
                'user_alt' => 'System',
                'action' => 'update',
                'table_name' => 'area_performance',
                'operation_type' => 'UPDATE',
                'old_value' => json_encode($this->getOriginal()),
                'new_value' => json_encode($this->getAttributes()),
                'description' => 'System added hours to area performance data',
            ]);
        } catch (\Exception $e) {
            AuditLog::create([
                'user_id' => (int) auth()->user()->id,
                'user_alt' => 'System',
                'action' => 'update',
                'operation_type' => 'UPDATE',
                'description' => 'Failed to add hours to area performance data.\n' . $e->getMessage(),
            ]);
        }
    }
}

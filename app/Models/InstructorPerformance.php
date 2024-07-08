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
        'score', 'total_hours', 'target_hours', 'sei_avg', 'enrolled_avg', 'dropped_avg', 'year', 'instructor_id',
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
        $courses = Teach::where('instructor_id', $instructor_id)
        ->whereHas('courseSection', function ($query) use ($year) {
            $query->where('year', $year);
        })->pluck('course_section_id');
                
        if (count($courses) === 0) {
            echo "No courses found for instructor ID: $instructor_id";
            return;
        }

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

        if($courseCount != 0) {
            $totalRoundedAvg = round($totalSumAverageScore/$courseCount, 1);
            $performance = self::where('instructor_id', $instructor_id)->where('year', $year)->first();
            if ($performance != null) {
                $performance->update([
                    'sei_avg' => $totalRoundedAvg,
                ]);
            }
        }

        return;

    }

    public function updateTotalHours($hours = [])
    {
        $totalHours = json_decode($this->total_hours, true);
        foreach ($hours as $month => $hour) {
            if ($month <= date('n')) {
                $totalHours[$month] = $hour;
            }
        }

        $this->total_hours = json_encode($totalHours);
        $this->save();
    }

    public function addHours($month, $hour)
    {
        $totalHours = json_decode($this->total_hours, true);
        $totalHours[$month] += $hour;
        $this->total_hours = json_encode($totalHours);
        $this->save();
    }
}

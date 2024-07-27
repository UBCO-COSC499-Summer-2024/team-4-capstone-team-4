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

    public static function updateInstructorSEIAvg($instructor_id, $year) {
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

            $seiData = SeiData::where('course_section_id', $course_id)->get();

            if(!$seiData->isEmpty()) {
                $courseCount++;
            }
            foreach($seiData as $data) {
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

    public static function updateInstructorEnrollAndDropAvg($instructor_id, $year) {
        $courseCount = 0;
        $totalSumEnrolledAvg = 0;
        $totalSumDroppedAvg = 0;

        $courses = Teach::where('instructor_id', $instructor_id)
        ->whereHas('courseSection', function ($query) use ($year) {
            $query->where('year', $year);
        })->pluck('course_section_id');

        foreach($courses as $course) {
            $courseSectionData = CourseSection::select('enrolled', 'dropped', 'capacity')->where('id', $course)->first();

            if($courseSectionData) {
                $enrolled = $courseSectionData->enrolled;
                $dropped = $courseSectionData->dropped;
                $capacity = $courseSectionData->capacity;

                $totalSumEnrolledAvg += $enrolled / $capacity;
                $totalSumDroppedAvg += $dropped / $capacity;

                $courseCount++;
            }
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

            $performance = self::where('instructor_id', $instructor_id)->where('year', $year)->first();
            if ($performance != null) {
                $performance->update([
                    'enrolled_avg' => $totalEnrolledPercent,
                    'dropped_avg' => $totalDroppedPercent,
                ]);
            }
        }
        return;
    }

    public static function updatePerformance($instructor_id, $year) {
        self::updateInstructorSEIAvg($instructor_id, $year);
        self::updateInstructorEnrollAndDropAvg($instructor_id, $year);


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
                'table_name' => 'instructor_performance',
                'operation_type' => 'UPDATE',
                'old_value' => json_encode($this->getOriginal()),
                'new_value' => json_encode($this->getAttributes()),
                'description' => 'System added hours to instructor performance data',
            ]);
        } catch (\Exception $e) {
            AuditLog::create([
                'user_id' => (int) auth()->user()->id,
                'user_alt' => 'System',
                'action' => 'update',
                'operation_type' => 'UPDATE',
                'description' => 'Failed to add hours to instructor performance data.\n' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Calculates the performance score for an instructor for a given year.
     *
     * This function calculates the total performance score based on the instructor's
     * assigned roles and course sections taught during the specified year. The score
     * considers the monthly service role hours, the number of course sections, and 
     * the difference between the enrolled and dropped averages.
     *
     * @param int $instructor_id The ID of the instructor.
     * @param int $currentYear The year for which the score is calculated.
     * @return float The calculated performance score.
     */
    public function scoreCalculator($instructor_id, $currentYear) {
        $roleHours = 0;
        $assignedRoles = RoleAssignment::where('instructor_id', $instructor_id)->get();

        foreach ($assignedRoles as $assignedRole) {
            $role = ServiceRole::where('id', $assignedRole->service_role_id)->where('year', $currentYear)->where('archived', false)->first();

            if ($role) {
                $serviceRoles[] = ['name' => $role->name, 'hours' => $role->monthly_hours[$currentMonth]];
                $roleHours += $role->monthly_hours[$currentMonth];
            }
        }

        $courseSections = 0;
        $doubleCourses = 0;
        $teaches = Teach::where('instructor_id', $instructor_id)->get();

        foreach ($teaches as $teaching) {
            $course = CourseSection::where('id', $teaching->course_section_id)->where('year', $currentYear)->where('archived', false)->first();
            
            if ($course) {
                if ($course->term === '1-2') {
                    $doubleCourses++;
                }

                else {
                    $courseSections++;
                }
            }
        }

        $enrolled = InstructorPerformance::where('instructor_id', $instructor_id)->first()->enrolled_avg;
        $dropped = InstructorPerformance::where('instructor_id', $instructor_id)->first()->dropped_avg;

        return ($roleHours + (((215 * $courseSections) + (530 * $doubleCourses)) * (($enrolled - $dropped) / $enrolled))) / 8760; 
    }
}

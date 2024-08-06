<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AreaPerformance;
use App\Models\UserRole;
use App\Models\Department;

class Area extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'areas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'dept_id',
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
     * Get the department that owns the area.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department() {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    /**
     * Get the instructors belonging to the area.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instructors()
    {
        return $this->hasMany(UserRole::class, 'area_id')->where('role', 'instructor');
    }

    /**
     * Get the performance associated with the area.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function areaPerformance() {
        return $this->hasMany(AreaPerformance::class, 'area_id');
    }

    // other functions

    public static function getAreasByDepartment() {
        return Area::with('department')->get()->groupBy('dept_id');
    }

    public static function getInstructors($area_id, $year){
        $courses = self::getCourseSections($area_id, $year);
        $instructors = collect();

        foreach($courses as $course) {
            if ($course->teaches) {
                $instructor = UserRole::find($course->teaches->instructor_id);
                if ($instructor) {
                    $instructors->push($instructor);
                }
            }
        }
        $uniqueInstructors = $instructors->unique('id');
    
        return $uniqueInstructors;
    }

    public static function getCourseSections($area_id, $year){
        return CourseSection::where('area_id', $area_id)->where('year', $year)->get();
    }

    public static function getServiceRoles($area_id, $year){
        return ServiceRole::where('area_id', $area_id)->where('year', $year)->get();
    }

    public static function getExtraHours($area_id, $year){
        return ExtraHour::where('area_id', $area_id)->where('year', $year)->get();
    }

}

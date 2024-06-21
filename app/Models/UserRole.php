<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RoleAssignment;
use App\Models\User;
use App\Models\Area;
use App\Models\InstructorPerformance;
use App\Models\ServiceRole;

class UserRole extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'area_id', 'role',
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
     * Get the user associated with the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department associated with the dept_head or dept_staff or instructor role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function department() {
        if ($this->role === 'dept_head' || $this->role === 'dept_staff' || $this->role === 'instructor') {
            return $this->belongsTo(Department::class);
        }
        
        return null; // Return null if the user is not an instructor or dept head or staff
    }

    /**
     * Get the roles assigned by dept_head or dept_staff role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|null
     */
    public function assignedRoles() {
        if ($this->role === 'dept_head' || $this->role === 'dept_staff') {
            return $this->hasMany(RoleAssignment::class, 'assigner_id');
        }
        
        return null; // Return null if the user is not a dept head or staff
    }

    /**
     * Get the performance associated with the instructor role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|null
     */
    public function instructorPerformance() {
        if ($this->role === 'instructor') {
            return $this->hasOne(InstructorPerformance::class, 'user_id');
        }
        
        return null; // Return null if the user is not an instructor
    }

    /**
     * Get the service roles associated with the instructor role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     */
    public function serviceRoles() {
        if ($this->role === 'instructor') {
            return $this->belongsToMany(ServiceRole::class, 'role_assignments', 'user_id', 'service_role_id')
                        ->withPivot('assigner_id')
                        ->withTimestamps();
        }
        
        return null; // Return null if the user is not an instructor
    }

    /**
     * Get the courses taught by the instructor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|null
     */
    public function teaches() {
        if ($this->role === 'instructor') {
            return $this->hasMany(Teach::class, 'instructor_id');
        }

        return null; // Return null if the user is not an instructor
    }

    public function areas()
    {
        return $this->belongsToMany(Area::class, 'teaches', 'instructor_id', 'course_section_id')
                    ->join('course_sections', 'course_sections.id', '=', 'teaches.course_section_id')
                    ->join('areas as a', 'a.id', '=', 'course_sections.area_id')
                    ->select('a.*')
                    ->distinct();
    }

    
}

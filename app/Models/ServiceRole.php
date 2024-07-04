<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InstructorPerformance;
use App\Models\AreaPerformance;
use App\Models\DepartmentPerformance;
use App\Models\UserRole;
use App\Models\Area;
use Illuminate\Support\Facades\DB;

class ServiceRole extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'year', 'monthly_hours', 'area_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'monthly_hours' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the area associated with the service role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area() {
        return $this->belongsTo(Area::class, 'area_id');
    }

    /**
     * Get the user roles assigned to this service role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userRoles()
    {
        return $this->belongsToMany(UserRole::class, 'role_assignments', 'service_role_id', 'instructor_id')
                    ->where('role', 'instructor')
                    ->withPivot('assigner_id')
                    ->withTimestamps();
    }

    public function roleAssignments()
    {
        return $this->hasMany(RoleAssignment::class, 'service_role_id');
    }

    public function users() {
        return $this->hasManyThrough(User::class, RoleAssignment::class,'service_role_id','id','id', 'instructor_id');
    }

    public function instructors() {
        // list of instructors assigned to this service role
        return $this->belongsToMany(User::class, 'role_assignments', 'service_role_id', 'instructor_id')
                    ->whereHas('roles', function ($query) {
                        $query->where('role', 'instructor');
                    });
    }

    public function areaPerformance() {
        return $this->hasOne(AreaPerformance::class, 'service_role_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DepartmentPerformance;
use App\Models\UserRole;
use App\Models\Area;

class Department extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'departments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
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
     * Get the areas belonging to the department.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areas() {
        return $this->hasMany(Area::class, 'dept_id');
    }

    /**
     * Get the department head belonging to the department.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function deptHead()
    {
        return $this->hasOne(UserRole::class, 'department_id')->where('role', 'dept_head');
    }

    /**
     * Get the department staff belonging to the department.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deptStaff()
    {
        return $this->hasMany(UserRole::class, 'department_id')->where('role', 'dept_staff');
    }

    /**
     * Get the performance associated with the department.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function departmentPerformance() {
        return $this->hasOne(DepartmentPerformance::class, 'department_id');
    }
}

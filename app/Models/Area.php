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
        return $this->hasOne(AreaPerformance::class, 'area_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserRole;
use App\Models\Area;

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
        'name', 'desc', 'year', 'monthly_hours', 'area_id',
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
        return $this->belongsToMany(UserRole::class, 'role_assignments', 'service_role_id', 'user_role_id')
                    ->where('role', 'instructor') 
                    ->withPivot('assigner_id')
                    ->withTimestamps();
    }
}

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

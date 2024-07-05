<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseSection;
use App\Models\TeachingAssistant;

class Assist extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cid', 'taid', 'rating',
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
     * Get the course section associated with the assist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courseSection() {
        return $this->belongsTo(CourseSection::class, 'course_id');
    }

    /**
     * Get the teaching assistant associated with the assist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teachingAssistant() {
        return $this->belongsTo(TeachingAssistant::class, 'ta_id');
    }
}

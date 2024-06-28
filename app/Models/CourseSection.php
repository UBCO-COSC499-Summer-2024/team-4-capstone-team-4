<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TeachingAssistant;
use App\Models\SeiData;
use App\Models\Teach;
use App\Models\Area;

class CourseSection extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'course_sections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'area_id', 'duration', 'enrolled', 'dropped', 'capacity', 'year', 'term', 'session'
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
     * Get the area associated with the course section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area() {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the SEI data associated with the course section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function seiData() {
        return $this->hasOne(SeiData::class);
    }

    /**
     * Get the teaching assistants assigned to the course section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teachingAssistants() {
        return $this->belongsToMany(TeachingAssistant::class, 'assists', 'course_section_id', 'ta_id')
                    ->withPivot('rating');
    }

    /**
     * Get the instructors teaching this course section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teaches() {
        return $this->hasMany(Teach::class, 'course_section_id');
    }
}

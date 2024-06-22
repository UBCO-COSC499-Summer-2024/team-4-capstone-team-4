<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseSection;

class SeiData extends Model {
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sei_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cid', 'q1im', 'q2im', 'q3im', 'q4im', 'q5im', 'q6im',
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
     * Get the course section associated with the SEI data.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courseSection() {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }
}

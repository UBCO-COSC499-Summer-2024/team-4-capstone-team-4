<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CourseSection;
use App\Models\UserRole;
use App\Models\Teach;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teach>
 */
class TeachFactory extends Factory
{
    protected $model=Teach::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_section_id'=>CourseSection::pluck('id')->random(),
            'instructor_id'=>UserRole::pluck('id')->random(),
        ];
    }
}

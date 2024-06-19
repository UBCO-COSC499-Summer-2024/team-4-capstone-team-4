<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CourseSection;
use App\Models\TeachingAssistant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assist>
 */
class AssistFactory extends Factory
{
    protected $model=\App\Models\Assist::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_section_id'=>CourseSection::pluck('id')->random(),
            'ta_id'=>TeachingAssistant::pluck('id')->random(),
            'rating'=>fake()->randomFloat(1,2,3,4,5),
        ];
    }
}

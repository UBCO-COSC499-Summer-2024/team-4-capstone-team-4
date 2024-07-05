<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CourseSection;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SeiData>
 */
class SeiDataFactory extends Factory {
    protected $model=\App\Models\SeiData::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'course_section_id'=>CourseSection::pluck('id')->random(),
            'questions'=>json_encode(fake()->randomElements(['Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Q6'],3)),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CourseSection;
use App\Models\Area;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseSection>
 */
class CourseSectionFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model=CourseSection::class;

    public function definition(): array {
        return [
            'name'=>fake()->name(),
            'area_id'=>Area::pluck('id')->random(),
            'enrolled'=>fake()->numberBetween(10,100),
            'dropped'=>fake()->numberBetween(0,20),
            'capacity'=>fake()->numberBetween(10,200),
            'year'=>fake()->year(),
        ];
    }
}

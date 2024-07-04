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

    public function definition() {
        $prefixes = ['COSC', 'MATH', 'STAT', 'PHYS'];
        return [
            'name'=>fake()->randomElement($prefixes).' '.fake()->numberBetween(100,500),
            'area_id'=>Area::pluck('id')->random(),
            'year'=>fake()->year(),
            'enrolled'=>fake()->numberBetween(10,100),
            'dropped'=>fake()->numberBetween(0,20),
            'capacity'=>fake()->numberBetween(10,200),
            'term' => fake()->randomElement(['1', '2', '1-2']),
            'session' => fake()->randomElement(['W', 'S']),
            'section' => fake()->randomElement(['001', '002', '003']),
        ];
    }
}

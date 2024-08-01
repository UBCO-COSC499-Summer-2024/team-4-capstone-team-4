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
     
Define the model's default state.*
@return array<string, mixed>*/
protected $model=CourseSection::class;

    public function definition() {
        $prefixes = ['COSC', 'MATH', 'PHYS', 'STAT'];
        $prefix = $this->faker->randomElement($prefixes);

        // Define a function to get or create an area and return its ID
        $getOrCreateAreaId = function($areaName) {
            $area = Area::where('name', $areaName)->first();
            if ($area == null) {
                $area = Area::factory()->create(['name' => $areaName]);
            }
            return $area->id;
        };

        switch ($prefix) {
            case 'COSC':
                $area_id = $getOrCreateAreaId('Computer Science');
                break;
            case 'MATH':
                $area_id = $getOrCreateAreaId('Mathematics');
                break;
            case 'PHYS':
                $area_id = $getOrCreateAreaId('Physics');
                break;
            case 'STAT':
            default:
                $area_id = $getOrCreateAreaId('Statistics');
                break;
        }

        $capacity = fake()->numberBetween(10, 200);
    
        return [
            'prefix' => $prefix,
            'number' => fake()->numberBetween(100, 500),
            'area_id' => $area_id,
            'year' => fake()->year(),
            'enroll_start' => fake()->numberBetween(10, $capacity-1),
            'enroll_end' => fake()->numberBetween(0, $capacity-1),
            'dropped' => fake()->numberBetween(0, 20),
            'capacity' => $capacity,
            'term' => fake()->randomElement(['1', '2', '1-2']),
            'session' => fake()->randomElement(['W', 'S']),
            'section' => fake()->randomElement(['001', '002', '003']),
            'room' => $this->faker->randomElement(['EME', 'FIP', 'ART', 'SCI', 'COM']) . ' ' . $this->faker->numberBetween(100, 500),
            'time' => $this->faker->time('H:i'),
        ];
    }
    
}
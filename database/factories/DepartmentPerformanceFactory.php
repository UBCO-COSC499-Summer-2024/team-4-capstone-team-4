<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Department;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DepartmentPerformance>
 */
class DepartmentPerformanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'score' => fake()->numberBetween(0, 100),
            'total_hours' => fake()->numberBetween(0, 100),
            'sei_avg' => fake()->numberBetween(1, 5),
            'year' => fake()->year(),
            'dept_id' => Department::pluck('id')->random()
        ];
    }
}

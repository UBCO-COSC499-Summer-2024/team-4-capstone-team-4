<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Department;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DepartmentPerformance>
 */
class DepartmentPerformanceFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $department = Department::inRandomOrder()->first();

        return [
            'total_hours'  => json_encode([
                'January' => $this->faker->numberBetween(10000, 20000),
                'February' => $this->faker->numberBetween(10000, 20000),
                'March' => $this->faker->numberBetween(10000, 20000),
                'April' => $this->faker->numberBetween(10000, 20000),
                'May' => $this->faker->numberBetween(10000, 20000),
                'June' => $this->faker->numberBetween(10000, 20000),
                'July' => $this->faker->numberBetween(10000, 20000),
                'August' => $this->faker->numberBetween(10000, 20000),
                'September' => $this->faker->numberBetween(10000, 20000),
                'October' => $this->faker->numberBetween(10000, 20000),
                'November' => $this->faker->numberBetween(10000, 20000),
                'December' => $this->faker->numberBetween(10000, 20000),
            ]),
            'sei_avg' => fake()->numberBetween(1, 5),
            'enrolled_avg'=>fake()->numberBetween(0,100),
            'dropped_avg'=>fake()->numberBetween(0,100),
            'year' => fake()->year(),
            'dept_id' => $department ? $department->id : Department::factory()->create()->id, 
        ];
    }
}
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
        return [
            'total_hours'  => json_encode([
                'January' => $this->faker->numberBetween(0, 730),
                'February' => $this->faker->numberBetween(0, 730),
                'March' => $this->faker->numberBetween(0, 730),
                'April' => $this->faker->numberBetween(0, 730),
                'May' => $this->faker->numberBetween(0, 730),
                'June' => $this->faker->numberBetween(0, 730),
                'July' => $this->faker->numberBetween(0, 730),
                'August' => $this->faker->numberBetween(0, 730),
                'September' => $this->faker->numberBetween(0, 730),
                'October' => $this->faker->numberBetween(0, 730),
                'November' => $this->faker->numberBetween(0, 730),
                'December' => $this->faker->numberBetween(0, 730),
            ]),
            'sei_avg' => fake()->numberBetween(1, 5),
            'enrolled_avg'=>fake()->numberBetween(0,100),
            'dropped_avg'=>fake()->numberBetween(0,100),
            'year' => fake()->year(),
            'dept_id' => Department::pluck('id')->random()
        ];
    }
}

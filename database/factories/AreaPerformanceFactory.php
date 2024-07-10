<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Area;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AreaPerformance>
 */
class AreaPerformanceFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'total_hours'  => json_encode([
                'January' => $this->faker->numberBetween(2000, 50000),
                'February' => $this->faker->numberBetween(2000, 50000),
                'March' => $this->faker->numberBetween(2000, 50000),
                'April' => $this->faker->numberBetween(2000, 50000),
                'May' => $this->faker->numberBetween(2000, 50000),
                'June' => $this->faker->numberBetween(2000, 50000),
                'July' => $this->faker->numberBetween(2000, 50000),
                'August' => $this->faker->numberBetween(2000, 50000),
                'September' => $this->faker->numberBetween(2000, 50000),
                'October' => $this->faker->numberBetween(2000, 50000),
                'November' => $this->faker->numberBetween(2000, 50000),
                'December' => $this->faker->numberBetween(2000, 50000),
            ]),
            'sei_avg' => fake()->numberBetween(1, 5),
            'enrolled_avg'=>fake()->numberBetween(0,100),
            'dropped_avg'=>fake()->numberBetween(0,100),
            'year' => fake()->year(),
            'area_id' => Area::pluck('id')->random()
        ];
    }
}

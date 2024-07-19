<?php

namespace Database\Factories;

use App\Models\InstructorPerformance;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstructorPerformanceFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InstructorPerformance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        // Ensure we have at least one UserRole with the role 'instructor'
        $instructor = UserRole::where('role', 'instructor')->inRandomOrder()->first();

        return [
            'score' => $this->faker->numberBetween(0, 100),
            'total_hours' => json_encode([
                'January' => $this->faker->numberBetween(0, 360),
                'February' => $this->faker->numberBetween(0, 360),
                'March' => $this->faker->numberBetween(0, 360),
                'April' => $this->faker->numberBetween(0, 360),
                'May' => $this->faker->numberBetween(0, 360),
                'June' => $this->faker->numberBetween(0, 360),
                'July' => $this->faker->numberBetween(0, 360),
                'August' => $this->faker->numberBetween(0, 360),
                'September' => $this->faker->numberBetween(0, 360),
                'October' => $this->faker->numberBetween(0, 360),
                'November' => $this->faker->numberBetween(0, 360),
                'December' => $this->faker->numberBetween(0, 360),
            ]),
            'target_hours' => $this->faker->numberBetween(1000, 2000),
            'sei_avg' => $this->faker->randomFloat(2, 1, 5),
            'enrolled_avg'=>fake()->numberBetween(0,100),
            'dropped_avg'=>fake()->numberBetween(0,100),
            'year' => $this->faker->year,
            'instructor_id' => $instructor ? $instructor->id : UserRole::factory()->create(['role' => 'instructor'])->id,
        ];
    }
}

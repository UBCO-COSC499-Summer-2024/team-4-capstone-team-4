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
            'target_hours' => $this->faker->numberBetween(0, 8760),
            'sei_avg' => $this->faker->randomFloat(2, 1, 5),
            'enrolled_avg'=>fake()->numberBetween(0,100),
            'dropped_avg'=>fake()->numberBetween(0,100),
            'year' => $this->faker->year,
            'instructor_id' => $instructor ? $instructor->id : UserRole::factory()->create(['role' => 'instructor'])->id,
        ];
    }
}

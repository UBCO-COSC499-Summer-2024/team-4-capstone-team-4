<?php

namespace Database\Factories;

use App\Models\InstructorPerformance;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstructorPerformanceFactory extends Factory
{
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
    public function definition()
    {
        // Ensure we have at least one UserRole with the role 'instructor'
        $instructor = UserRole::where('role', 'instructor')->inRandomOrder()->first();

        return [
            'score' => $this->faker->numberBetween(0, 100),
            'total_hours' => $this->faker->numberBetween(0, 200),
            'target_hours' => $this->faker->numberBetween(0, 200),
            'sei_avg' => $this->faker->randomFloat(2, 0, 5),
            'year' => $this->faker->year,
            'instructor_id' => $instructor ? $instructor->id : UserRole::factory()->create(['role' => 'instructor'])->id,
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Department;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Area>
 */
class AreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $areas = ['Mathematics', 'Statistics', 'Computer Science', 'Physics'];

        return [
            'name' => fake()->randomElement($areas),
            'dept_id' => Department::pluck('id')->random()
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\ApprovalType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApprovalType>
 */
class ApprovalTypeFactory extends Factory
{

    protected $model = ApprovalType::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = ['Approval Type 1', 'Approval Type 2', 'Approval Type 3', 'Approval Type 4', 'Approval Type 5'];
        return [
            'name' => fake()->randomElement($names),
            'description' => fake()->sentence(),
            'approvals_required' => fake()->numberBetween(1, 10),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\ApprovalStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApprovalStatus>
 */
class ApprovalStatusFactory extends Factory
{
    protected $model = ApprovalStatus::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = ['pending', 'approved', 'rejected', 'cancelled', 'intermediate'];
        return [
            'name' => fake()->randomElement($names),
            'description' => fake()->sentence(),
        ];
    }
}

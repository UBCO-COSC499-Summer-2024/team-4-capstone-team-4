<?php

namespace Database\Factories;

use App\Models\Approval;
use App\Models\ApprovalStatus;
use App\Models\ApprovalType;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Approval>
 */
class ApprovalFactory extends Factory
{

    protected $model = Approval::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'approval_type_id' => ApprovalType::factory(),
            'status_id' => ApprovalStatus::factory(),
            'user_id' => User::factory(),
            'details' => fake()->sentence(),
            'approved_at' => fake()->optional()->dateTimeThisYear(),
            'rejected_at' => fake()->optional()->dateTimeThisYear(),
            'approved_by' => UserRole::factory(),
            'rejected_by' => UserRole::factory(),
            'created_at' => fake()->dateTimeThisYear(),
            'updated_at' => fake()->dateTimeThisYear(),
            'active' => fake()->boolean(),
        ];
    }
}

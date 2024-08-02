<?php

namespace Database\Factories;

use App\Models\Approval;
use App\Models\ApprovalHistory;
use App\Models\ApprovalStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApprovalHistory>
 */
class ApprovalHistoryFactory extends Factory
{
    protected $model = ApprovalHistory::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'approval_id' => Approval::factory(),
            'status_id' => ApprovalStatus::factory(),
            'user_id' => User::factory(),
            'remarks' => fake()->sentence(),
            'created_at' => fake()->dateTimeThisYear(),
            'updated_at' => fake()->dateTimeThisYear(),
            'changed_at' => fake()->dateTimeThisYear(),
        ];
    }
}

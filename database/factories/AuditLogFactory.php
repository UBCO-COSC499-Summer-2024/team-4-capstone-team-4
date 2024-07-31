<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuditLog>
 */
class AuditLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'user_alt' => $this->faker->userName,
            'action' => $this->faker->randomElement(['create', 'update', 'delete']),
            'description' => $this->faker->sentence,
            'table_name' => $this->faker->word,
            'operation_type' => $this->faker->randomElement(['insert', 'update', 'delete']),
            'old_value' => json_encode(['name' => 'Old Name']),
            'new_value' => json_encode(['name' => 'New Name']),
            'timestamp' => $this->faker->dateTimeThisYear,
        ];
    }
}

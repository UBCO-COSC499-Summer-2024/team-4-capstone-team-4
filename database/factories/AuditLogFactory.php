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
            'user_id' => $this->faker->optional()->randomNumber(),
            'user_alt' => $this->faker->userName,
            'action' => $this->faker->word,
            'description' => $this->faker->optional()->paragraph,
            'table_name' => $this->faker->word,
            'operation_type' => $this->faker->randomElement(['insert', 'update', 'delete']),
            'old_value' => $this->faker->optional()->json,
            'new_value' => $this->faker->optional()->json,
            'timestamp' => $this->faker->dateTime,
        ];
    }
}

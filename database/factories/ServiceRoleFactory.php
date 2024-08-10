<?php

namespace Database\Factories;

use App\Models\ServiceRole;
use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceRoleFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceRole::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        // Ensure we have at least one Area
        $area = Area::inRandomOrder()->first();

        return [
            'name' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(),
            'year' => $this->faker->year(),
            'monthly_hours' => [
                'January' => $this->faker->numberBetween(0, 200),
                'February' => $this->faker->numberBetween(0, 200),
                'March' => $this->faker->numberBetween(0, 200),
                'April' => $this->faker->numberBetween(0, 200),
                'May' => $this->faker->numberBetween(0, 200),
                'June' => $this->faker->numberBetween(0, 200),
                'July' => $this->faker->numberBetween(0, 200),
                'August' => $this->faker->numberBetween(0, 200),
                'September' => $this->faker->numberBetween(0, 200),
                'October' => $this->faker->numberBetween(0, 200),
                'November' => $this->faker->numberBetween(0, 200),
                'December' => $this->faker->numberBetween(0, 200),
            ],
            'area_id' => $area ? $area->id : Area::factory()->create()->id,
        ];
    }
}

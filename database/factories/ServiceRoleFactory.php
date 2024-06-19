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
                'January' => $this->faker->numberBetween(0, 100),
                'February' => $this->faker->numberBetween(0, 100),
                'March' => $this->faker->numberBetween(0, 100),
                'April' => $this->faker->numberBetween(0, 100),
                'May' => $this->faker->numberBetween(0, 100),
                'June' => $this->faker->numberBetween(0, 100),
                'July' => $this->faker->numberBetween(0, 100),
                'August' => $this->faker->numberBetween(0, 100),
                'September' => $this->faker->numberBetween(0, 100),
                'October' => $this->faker->numberBetween(0, 100),
                'November' => $this->faker->numberBetween(0, 100),
                'December' => $this->faker->numberBetween(0, 100),
            ],
            'area_id' => $area ? $area->id : Area::factory()->create()->id,
        ];
    }
}

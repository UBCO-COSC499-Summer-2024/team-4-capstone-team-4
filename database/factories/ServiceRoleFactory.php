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
                'January' => $this->faker->numberBetween(0, 730),
                'February' => $this->faker->numberBetween(0, 730),
                'March' => $this->faker->numberBetween(0, 730),
                'April' => $this->faker->numberBetween(0, 730),
                'May' => $this->faker->numberBetween(0, 730),
                'June' => $this->faker->numberBetween(0, 730),
                'July' => $this->faker->numberBetween(0, 730),
                'August' => $this->faker->numberBetween(0, 730),
                'September' => $this->faker->numberBetween(0, 730),
                'October' => $this->faker->numberBetween(0, 730),
                'November' => $this->faker->numberBetween(0, 730),
                'December' => $this->faker->numberBetween(0, 730),
            ],
            'area_id' => $area ? $area->id : Area::factory()->create()->id,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\ExtraHour;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Area;

class ExtraHourFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExtraHour::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        // Ensure we have at least one User Role with the role 'dept_head' or 'dept_staff'
        $assigner = UserRole::whereIn('role', ['dept_head', 'dept_staff'])->inRandomOrder()->first();

        // Ensure we have at least one User Role with the role 'instructor'
        $instructor = UserRole::where('role', 'instructor')->inRandomOrder()->first();

        // Ensure we have at least one Area 
        $area = Area::inRandomOrder()->first();

        return [
            'name' => $this->faker->randomElement(['Meeting', 'Committee', 'Conference']),
            'description' => $this->faker->sentence,
            'hours' => $this->faker->numberBetween(1, 730),
            'year'=> $this->faker->year(),
            'month' => $this->faker->numberBetween(1, 12),
            'assigner_id' => $assigner ? $assigner->id : UserRole::factory()->create(['role' => 'dept_head'])->id,
            'instructor_id' => $instructor ? $instructor->id : UserRole::factory()->create(['role' => 'instructor'])->id,
            'area_id' => $area ? $area->id : Area::factory()->create()->id,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory {
    protected $model = Area::class;

    public function definition() {
        $department = Department::inRandomOrder()->first();

        return [
            'name' => $this->faker->word,
            'dept_id' => $department ? $department->id : Department::factory()->create()->id, 
        ];
    }
}

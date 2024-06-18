<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'dept_id' => Department::factory(), 
        ];
    }
}

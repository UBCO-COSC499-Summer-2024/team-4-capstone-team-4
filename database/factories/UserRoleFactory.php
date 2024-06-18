<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Area;
use App\Models\Department;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserRoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserRole::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Define a random role from the allowed roles
        $roles = ['instructor', 'dept_head', 'dept_staff'];
        $role = $this->faker->randomElement($roles);

        // Default user_id
        $user_id = User::factory();

        // Default area_id and department_id
        $area_id = null;
        $department_id = null;

        // Depending on the role, we might need to assign an area_id or department_id
        if ($role === 'instructor') {
            $area_id = Area::factory();
        } else if (in_array($role, ['dept_head', 'dept_staff'])) {
            $department_id = Department::factory();
        }

        return [
            'user_id' => $user_id,
            'area_id' => $area_id,
            'role' => $role,
        ];
    }
}

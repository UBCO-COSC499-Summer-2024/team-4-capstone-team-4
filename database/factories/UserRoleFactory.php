<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Department;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserRoleFactory extends Factory {
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
    public function definition() {
        // Define a random role from the allowed roles
        $roles = ['instructor', 'dept_head', 'dept_staff', 'admin'];
        $role = $this->faker->randomElement($roles);

        // Default user_id
        $user_id = User::factory()->create()->id;

        // Default department_id
        $department_id = null;

        // Depending on the role, we might need to assign a department_id
        if (in_array($role, ['dept_head', 'dept_staff', 'instructor'])) {
            $department_id = Department::factory()->create()->id;
        }

        return [
            'user_id' => $user_id,
            'department_id' => $department_id,
            'role' => $role,
        ];
    }
}

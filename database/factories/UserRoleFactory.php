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

        // Fetch a random user or create one if no users exist
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        // Fetch a random department or create one if no departments exist
        $department = Department::inRandomOrder()->first() ?? Department::factory()->create();

        // Determine the department_id based on the role
        $departmentId = in_array($role, ['dept_head', 'dept_staff', 'instructor']) ? $department->id : null;

        return [
            'user_id' => $user->id,
            'department_id' => $departmentId,
            'role' => $role,
        ];
    }
}

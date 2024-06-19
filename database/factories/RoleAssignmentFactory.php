<?php

namespace Database\Factories;

use App\Models\RoleAssignment;
use App\Models\ServiceRole;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleAssignmentFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RoleAssignment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        // Ensure we have at least one ServiceRole and UserRoles for assigner and instructor
        $serviceRole = ServiceRole::inRandomOrder()->first();
        $assigner = UserRole::whereIn('role', ['dept_head', 'dept_staff'])->inRandomOrder()->first();
        $instructor = UserRole::where('role', 'instructor')->inRandomOrder()->first();

        return [
            'service_role_id' => $serviceRole ? $serviceRole->id : ServiceRole::factory()->create()->id,
            'assigner_id' => $assigner ? $assigner->id : UserRole::factory()->create(['role' => 'dept_head'])->id,
            'instructor_id' => $instructor ? $instructor->id : UserRole::factory()->create(['role' => 'instructor'])->id,
        ];
    }
}

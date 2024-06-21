<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use App\Models\RoleAssignment;
use App\Models\UserRole;
use App\Models\ServiceRole;

class RoleAssignmentTest extends TestCase {
    /**
     * Test if role_assignments table exists.
     *
     * @return void
     */
    public function test_role_assignment_table_exists() {
        $this->assertTrue(
            Schema::hasTable('role_assignments'),
            'Role_assignment table does not exist'
        );
    }

    /**
     * Test creating a role assignment tracker.
     *
     * @return void
     */
    public function test_instructor_performance_can_be_created() {
        // Create an instructor & an assigner
        $instructor = UserRole::factory()->create();
        $assigner = UserRole::factory()->create();
        $serviceRole = ServiceRole::factory()->create();

        // Create an instructor performance
        $roleAssignment = RoleAssignment::factory()->create();([
            'instructor_id'=> $instructor->id,
            'assigner_id'=> $assigner->id,
            'service_role_id' => $serviceRole->id,
        ]);

        // Assert that the role assignment model exists
        $this->assertModelExists($roleAssignment);

        // Assert that the role assignment was created successfully 
        $this->assertInstanceOf(RoleAssignment::class, $roleAssignment);

        // Assert that the role assignment has an instructor
        $this->assertNotEmpty($roleAssignment->instructor_id);  

        // Assert that the role assignment has an assigner
        $this->assertNotEmpty($roleAssignment->assigner_id);  

        // Assert that the role assignment has an associated service role
        $this->assertNotEmpty($roleAssignment->service_role_id);   
    }
}

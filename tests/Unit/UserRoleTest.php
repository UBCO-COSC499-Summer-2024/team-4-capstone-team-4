<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\UserRole;
use App\Models\User;
use App\Models\Area;
use App\Models\Department;

class UserRoleTest extends TestCase
{
    use RefreshDatabase; // Ensure database is refreshed before each test

    /**
     * Test retrieving the associated user for any role.
     *
     * @return void
     */
    public function test_user_relation()
    {
        // Create a user and a user role associated with that user
        $user = User::factory()->create();
        $userRole = UserRole::factory()->create(['user_id' => $user->id]);

        // Retrieve the associated user using the relation
        $retrievedUser = $userRole->user;

        // Assert that the retrieved user matches the created user
        $this->assertInstanceOf(User::class, $retrievedUser);
        $this->assertEquals($user->id, $retrievedUser->id);
    }

    /**
     * Test retrieving the associated user for an instructor role.
     *
     * @return void
     */
    public function test_instructor_user_relation()
    {
        // Create test data
        $user = User::factory()->create();
        $userRole = UserRole::factory()->create(['role' => 'instructor', 'user_id' => $user->id]);

        // Retrieve the associated user using the relation
        $retrievedUser = $userRole->user;

        // Assert that the retrieved user matches the created user
        $this->assertInstanceOf(User::class, $retrievedUser);
        $this->assertEquals($user->id, $retrievedUser->id);
    }

    /**
     * Test retrieving the associated area for an instructor role.
     *
     * @return void
     */
    public function test_instructor_area_relation()
    {
        // Create test data
        $area = Area::factory()->create();
        $userRole = UserRole::factory()->create(['role' => 'instructor', 'area_id' => $area->id]);

        // Retrieve the associated area using the relation
        $retrievedArea = $userRole->area;

        // Assert that the retrieved area matches the created area
        $this->assertInstanceOf(Area::class, $retrievedArea);
        $this->assertEquals($area->id, $retrievedArea->id);
    }

    /**
     * Test retrieving the associated department for a dept_head role.
     *
     * @return void
     */
    public function test_dept_head_department_relation()
    {
        // Create test data
        $department = Department::factory()->create();
        $userRole = UserRole::factory()->create(['role' => 'dept_head', 'department_id' => $department->id]);

        // Retrieve the associated department using the relation
        $retrievedDepartment = $userRole->department;

        // Assert that the retrieved department matches the created department
        $this->assertInstanceOf(Department::class, $retrievedDepartment);
        $this->assertEquals($department->id, $retrievedDepartment->id);
    }

    /**
     * Test retrieving the associated department for a dept_staff role.
     *
     * @return void
     */
    public function test_dept_staff_department_relation()
    {
        // Create test data
        $department = Department::factory()->create();
        $userRole = UserRole::factory()->create(['role' => 'dept_staff', 'department_id' => $department->id]);

        // Retrieve the associated department using the relation
        $retrievedDepartment = $userRole->department;

        // Assert that the retrieved department matches the created department
        $this->assertInstanceOf(Department::class, $retrievedDepartment);
        $this->assertEquals($department->id, $retrievedDepartment->id);
    }

    /**
     * Test multiple associations for a user role.
     *
     * @return void
     */
    public function test_multiple_associations()
    {
        // Create test data
        $user = User::factory()->create();
        $area1 = Area::factory()->create();
        $area2 = Area::factory()->create();
        $department1 = Department::factory()->create();
        $department2 = Department::factory()->create();

        // Create user roles with multiple associations
        $userRole1 = UserRole::factory()->create([
            'user_id' => $user->id,
            'area_id' => $area1->id,
            'role' => 'instructor',
        ]);
        $userRole2 = UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $department1->id,
            'role' => 'dept_head',
        ]);

        // Test user relation
        $this->assertInstanceOf(User::class, $userRole1->user);
        $this->assertEquals($user->id, $userRole1->user->id);

        // Test area relation for instructor role
        $this->assertInstanceOf(Area::class, $userRole1->area);
        $this->assertEquals($area1->id, $userRole1->area->id);

        // Test department relation for dept_head role
        $this->assertInstanceOf(Department::class, $userRole2->department);
        $this->assertEquals($department1->id, $userRole2->department->id);
    }

    /**
     * Test performance and scalability.
     *
     * @return void
     */
    public function test_performance_and_scalability()
    {
        // Create a large number of user roles
        UserRole::factory()->count(100)->create(['role' => 'instructor']);

        // Retrieve all instructors
        $instructors = UserRole::where('role', 'instructor')->get();

        // Assert that the retrieved instructors are correct
        $this->assertEquals(100, $instructors->count());
    }
}
<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\UserRole;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Schema;

class UserRoleTest extends TestCase {

    use RefreshDatabase; // Ensure database is refreshed before each test

    /**
     * Test if user_roles table exists.
     *
     * @return void
     */
    public function test_user_roles_table_exists() {
        $this->assertTrue(
            Schema::hasTable('user_roles'),
            'user_roles table does not exist'
        );
    }

    /**
     * Test creating a user role.
     *
     * @return void
     */
    public function test_user_role_can_be_created(): void {
        $userRole = UserRole::factory()->create();

        // Assert that the user role model exists
        $this->assertModelExists($userRole);

        // Assert that the user role was created successfully 
        $this->assertInstanceOf(UserRole::class, $userRole);

        // Assert that the department has a role
        $this->assertNotEmpty($userRole->role);  

        // Assert that the department has a user
        $this->assertNotEmpty($userRole->user_id); 

        // Assert that the department has a department
        $this->assertNotEmpty($userRole->department_id); 
    }

    /**
     * Test retrieving the associated user for any role.
     *
     * @return void
     */
    public function test_user_relation() {
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
    public function test_instructor_user_relation() {
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
     * Test retrieving the associated user for an instructor role.
     *
     * @return void
     */
    public function test_dept_head_user_relation() {
        // Create test data
        $user = User::factory()->create();
        $userRole = UserRole::factory()->create(['role' => 'dept_head', 'user_id' => $user->id]);

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
    public function test_dept_staff_user_relation() {
        // Create test data
        $user = User::factory()->create();
        $userRole = UserRole::factory()->create(['role' => 'dept_staff', 'user_id' => $user->id]);

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
    public function test_admin_user_relation() {
        // Create test data
        $user = User::factory()->create();
        $userRole = UserRole::factory()->create(['role' => 'admin', 'user_id' => $user->id]);

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
    public function test_instructor_department_relation() {
        // Create test data
        $department = Department::factory()->create();
        $userRole = UserRole::factory()->create(['role' => 'instructor', 'department_id' => $department->id]);

        // Retrieve the associated area using the relation
        $retrievedDepartment = $userRole->department;

        // Assert that the retrieved area matches the created area
        $this->assertInstanceOf(Department::class, $retrievedDepartment);
        $this->assertEquals($department->id, $retrievedDepartment->id);
    }

    /**
     * Test retrieving the associated department for a dept_head role.
     *
     * @return void
     */
    public function test_dept_head_department_relation() {
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
    public function test_dept_staff_department_relation() {
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
    public function test_multiple_associations() {
        // Create test data
        $user = User::factory()->create();
        $department1 = Department::factory()->create();
        $department2 = Department::factory()->create();

        // Create user roles with multiple associations
        $userRole1 = UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $department1->id,
            'role' => 'instructor',
        ]);
        $userRole2 = UserRole::factory()->create([
            'user_id' => $user->id,
            'department_id' => $department2->id,
            'role' => 'dept_head',
        ]);

        // Test user relation
        $this->assertInstanceOf(User::class, $userRole1->user);
        $this->assertEquals($user->id, $userRole1->user->id);

        // Test department relation for instructor role
        $this->assertInstanceOf(Department::class, $userRole1->department);
        $this->assertEquals($department1->id, $userRole1->department->id);

        // Test department relation for dept_head role
        $this->assertInstanceOf(Department::class, $userRole2->department);
        $this->assertEquals($department2->id, $userRole2->department->id);
    }
}
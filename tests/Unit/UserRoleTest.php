<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\UserRole;
use App\Models\User;
use App\Models\Area;
use App\Models\Department;
use App\Models\RoleAssignment;
use App\Models\InstructorPerformance;
use App\Models\ServiceRole;
use App\Models\Teach;

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
     * Test retrieving null when there is no associated user.
     *
     * @return void
     */
    //public function test_user_relation_returns_null_when_not_associated()
    //{
        // Create a user role without associating it with any user
        //$userRole = UserRole::factory()->create(['user_id' => null]);

        // Retrieve the associated user using the relation
        //$retrievedUser = $userRole->user;

        // Assert that the retrieved user is null
        //$this->assertNull($retrievedUser);
    //}

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
     * Test retrieving the assigned roles by a dept_head or dept_staff role.
     *
     * @return void
     */
    //public function test_dept_head_assigned_roles_relation()
    //{
        // Create test data
        //$assigner = UserRole::factory()->create(['role' => 'dept_head']);
        //$userRole = UserRole::factory()->create(['role' => 'dept_staff', 'department_id' => $assigner->department_id]);
        //RoleAssignment::factory()->create(['assigner_id' => $assigner->id, 'instructor_id' => $userRole->id]);

        // Retrieve the assigned roles using the relation
        //$assignedRoles = $userRole->assignedRoles;

        // Assert that the assigned roles are retrieved correctly
        //$this->assertInstanceOf(RoleAssignment::class, $assignedRoles->first());
        //$this->assertEquals(1, $assignedRoles->count());
    //}

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
     * Test retrieving the instructor performance for an instructor role.
     *
     * @return void
     */
    //public function test_instructor_performance_relation()
    //{
        // Create test data
        //$userRole = UserRole::factory()->create(['role' => 'instructor']);
        //$instructorPerformance = InstructorPerformance::factory()->create(['instructor_id' => $userRole->id]);

        // Retrieve the instructor performance using the relation
        //$retrievedPerformance = $userRole->instructorPerformance;

        // Assert that the retrieved performance matches the created instructor performance
        //$this->assertInstanceOf(InstructorPerformance::class, $retrievedPerformance);
        //$this->assertEquals($instructorPerformance->id, $retrievedPerformance->id);
    //}

    /**
     * Test retrieving the service roles for an instructor role.
     *
     * @return void
     */
    //public function test_instructor_service_roles_relation()
    //{
        // Create test data
        //$userRole = UserRole::factory()->create(['role' => 'instructor']);
        //$serviceRole = ServiceRole::factory()->create();
        //$userRole->serviceRoles()->attach($serviceRole->id, ['assigner_id' => UserRole::factory()->create()->id]);

        // Retrieve the service roles using the relation
        //$serviceRoles = $userRole->serviceRoles;

        // Assert that the retrieved service roles are correct
        //$this->assertInstanceOf(ServiceRole::class, $serviceRoles->first());
        //$this->assertEquals(1, $serviceRoles->count());
    //}

    /**
     * Test retrieving the courses taught by an instructor.
     *
     * @return void
     */
    //public function test_instructor_courses_taught_relation()
    //{
        // Create test data
        //$userRole = UserRole::factory()->create(['role' => 'instructor']);
        //$course = Teach::factory()->create(['user_role_id' => $userRole->id]);

        // Retrieve the courses taught by the instructor using the relation
        //$coursesTaught = $userRole->teaches;

        // Assert that the retrieved courses are correct
        //$this->assertInstanceOf(Teach::class, $coursesTaught->first());
        //$this->assertEquals(1, $coursesTaught->count());
    //}

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
     * Test null associations for a user role.
     *
     * @return void
     */
    //public function test_null_associations_when_not_associated()
    //{
        // Create user role without associations
        //$userRole = UserRole::factory()->create([
            //'user_id' => null,
            //'area_id' => null,
            //'department_id' => null,
        //]);

        // Test user relation
        //$this->assertNull($userRole->user);

        // Test area relation
        //$this->assertNull($userRole->area);

        // Test department relation
        //$this->assertNull($userRole->department);
    //}

    /**
     * Test invalid role value handling.
     *
     * @return void
     */
    //public function test_invalid_role_value_handling()
    //{
        // Create user role with an invalid role value
        //$userRole = UserRole::factory()->create(['role' => 'invalid_role']);

        // Test area relation
        //$this->assertNull($userRole->area);

        // Test department relation
        //$this->assertNull($userRole->department);

        // Test assigned roles relation
        //$this->assertNull($userRole->assignedRoles);

        // Test instructor performance relation
        //$this->assertNull($userRole->instructorPerformance);

        // Test service roles relation
        //$this->assertNull($userRole->serviceRoles);

        // Test teaches relation
        //$this->assertNull($userRole->teaches);
    //}

    /**
     * Test empty or incorrect data handling.
     *
     * @return void
     */
    //public function test_empty_or_incorrect_data_handling()
    //{
        // Create user role with empty associations
        //$userRole = UserRole::factory()->create([
            //'user_id' => null,
            //'area_id' => null,
            //'department_id' => null,
        //]);

        // Test user relation
        //$this->assertNull($userRole->user);

        // Test area relation
        //$this->assertNull($userRole->area);

        // Test department relation
        //$this->assertNull($userRole->department);
    //}

    /**
     * Test role-specific behaviors for instructor role.
     *
     * @return void
     */
    //public function test_role_specific_behaviors_instructor_role()
    //{
        // Create test data
        //$userRole = UserRole::factory()->create(['role' => 'instructor']);

        // Test instructor performance relation
        //$this->assertNull($userRole->instructorPerformance); // Assuming no performance data is associated

        // Test service roles relation
        //$this->assertEquals(0, $userRole->serviceRoles->count());

        // Test teaches relation
        //$this->assertEquals(0, $userRole->teaches->count());
    //}

    /**
     * Test concurrent operations.
     *
     * @return void
     */
    //public function test_concurrent_operations()
    //{
        // Create test data
        //$userRole = UserRole::factory()->create(['role' => 'instructor']);
        //$course1 = Teach::factory()->create(['user_role_id' => $userRole->id]);
        //$course2 = Teach::factory()->create(['user_role_id' => $userRole->id]);

        // Retrieve courses taught by the instructor using the relation
        //$coursesTaught = $userRole->teaches;

        // Assert that the retrieved courses are correct
        //$this->assertInstanceOf(Teach::class, $coursesTaught->first());
        //$this->assertEquals(2, $coursesTaught->count());
    //}

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

    /**
     * Test boundary values.
     *
     * @return void
     */
    //public function test_boundary_values()
    //{
        // Create user role with boundary values
        //$userRole = UserRole::factory()->create([
            //'user_id' => 1,
            //'area_id' => 1,
            //'department_id' => 1,
        //]);

        // Test user relation
        //$this->assertInstanceOf(User::class, $userRole->user);
        //$this->assertEquals(1, $userRole->user->id);

        // Test area relation
        //$this->assertInstanceOf(Area::class, $userRole->area);
        //$this->assertEquals(1, $userRole->area->id);

        // Test department relation
        //$this->assertInstanceOf(Department::class, $userRole->department);
        //$this->assertEquals(1, $userRole->department->id);
    //}

    /**
     * Test exception handling.
     *
     * @return void
     */
    //public function test_exception_handling()
    //{
        // Ensure proper exception handling for invalid associations
        //$this->expectException(\Illuminate\Database\Eloquent\RelationNotFoundException::class);
        //$userRole = UserRole::factory()->create(['area_id' => 999]); // Non-existent area ID
        //$userRole->area; // Accessing relation should throw an exception
    //}

}
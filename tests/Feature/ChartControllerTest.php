<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\ChartController;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserRole;
use App\Models\DepartmentPerformance;
use App\Models\InstructorPerformance;
use App\Models\AreaPerformance;
use App\Models\Department;
use App\Models\Area;
use ReflectionMethod;

class ChartControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the showChart method for a department head.
     *
     * @return void
     */
    public function testShowChartForDepartmentHead()
    {
        // Create department performance data for testing
        $department = Department::factory()->create();

        // Create a user with the 'dept_head' role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'dept_head', 'department_id' => $department->id]);

        // Simulate authentication
        $this->actingAs($user);

        // Create department performance data for testing
        DepartmentPerformance::factory()->create([
            'dept_id' => $department->id,
            'year' => date('Y'),
            'sei_avg' => 4.5,
            'enrolled_avg' => 30,
            'dropped_avg' => 2,
            'total_hours' => json_encode([
                'January' => 600,
                'February' => 600,
                'March' => 600,
                'April' => 600,
                'May' => 600,
                'June' => 600,
                'July' => 600,
                'August' => 600,
                'September' => 600,
                'October' => 600,
                'November' => 600,
                'December' => 600,
            ]),
        ]);

        // Call the controller method
        $response = $this->get('/dashboard');

        // Assert the response
        $response->assertStatus(200); 
        $response->assertViewHas('chart1');
    }

    /**
     * Test the showChart method for a department staff.
     *
     * @return void
     */
    public function testShowChartForDepartmentStaff()
    {
        // Create department performance data for testing
        $department = Department::factory()->create();

        // Create a user with the 'dept_head' role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'dept_staff', 'department_id' => $department->id]);

        // Simulate authentication
        $this->actingAs($user);

        // Create department performance data for testing
        DepartmentPerformance::factory()->create([
            'dept_id' => $department->id,
            'year' => date('Y'),
            'sei_avg' => 4.5,
            'enrolled_avg' => 30,
            'dropped_avg' => 2,
            'total_hours' => json_encode([
                'January' => 600,
                'February' => 600,
                'March' => 600,
                'April' => 600,
                'May' => 600,
                'June' => 600,
                'July' => 600,
                'August' => 600,
                'September' => 600,
                'October' => 600,
                'November' => 600,
                'December' => 600,
            ]),
        ]);

        // Call the controller method
        $response = $this->get('/dashboard');

        // Assert the response
        $response->assertStatus(200); 
        $response->assertViewHas('chart1');
    }

    /**
     * Test the showChart method for an instructor.
     *
     * @return void
     */
    public function testShowChartForInstructor()
    {
        // Create a user with the 'instructor' role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'instructor']);

        // Simulate authentication
        $this->actingAs($user);

        // Create instructor performance data for testing
        InstructorPerformance::factory()->create([
            'instructor_id' => $user->id,
            'score' => 79,
            'year' => date('Y'),
            'sei_avg' => 4.2,
            'enrolled_avg' => 25,
            'dropped_avg' => 3,
            'target_hours' => 200,
            'total_hours' => json_encode([
                'January' => 600,
                'February' => 600,
                'March' => 600,
                'April' => 600,
                'May' => 600,
                'June' => 600,
                'July' => 600,
                'August' => 600,
                'September' => 600,
                'October' => 600,
                'November' => 600,
                'December' => 600,
            ]),
        ]);

        // Call the controller method
        $response = $this->get('/dashboard'); 

        // Assert the response
        $response->assertStatus(200); 
        $response->assertViewHas('chart1');
        $response->assertViewHas('chart2');
    }

    /**
     * Test the createPerformance method for instructor.
     *
     * @return void
     */
    public function testCreatePerformanceInstructor()
    {
        // Create a user with the 'instructor' role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'instructor']);

        // Simulate authentication
        $this->actingAs($user);

        // Call the controller method
        $response = $this->get('/dashboard'); 

        // Assert the response
        $response->assertStatus(200); 
    }

    /**
     * Test the createPerformance method for department head.
     *
     * @return void
     */
    public function testCreatePerformanceDepartmentHead()
    {
        // Create department performance data for testing
        $department = Department::factory()->create();

        // Create a user with the 'dept_head' role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'dept_head', 'department_id' => $department->id]);

        // Simulate authentication
        $this->actingAs($user);

        // Call the controller method
        $response = $this->get('/dashboard');

        // Assert the response
        $response->assertStatus(200); 
    }

    /**
     * Test the createPerformance method for department staff.
     *
     * @return void
     */
    public function testCreatePerformanceDepartmentStaff()
    {
        // Create department performance data for testing
        $department = Department::factory()->create();

        // Create a user with the 'dept_head' role
        $user = User::factory()->create();
        UserRole::factory()->create(['user_id' => $user->id, 'role' => 'dept_staff', 'department_id' => $department->id]);

        // Simulate authentication
        $this->actingAs($user);

        // Call the controller method
        $response = $this->get('/dashboard');

        // Assert the response
        $response->assertStatus(200); 
    }
}



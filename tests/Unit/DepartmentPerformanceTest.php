<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Department;
use App\Models\DepartmentPerformance;
use Illuminate\Support\Facades\Schema;

class DepartmentPerformanceTest extends TestCase {
    /**
     * Test if department performance table exists.
     *
     * @return void
     */
    public function test_department_performance_table_exists() {
        $this->assertTrue(
            Schema::hasTable('department_performance'),
            'department_performance table does not exist'
        );
    }

    /**
     * Test creating a department performance.
     *
     * @return void
     */
    public function test_department_performance_can_be_created() {
        // Create a department performance
        $department_performance = DepartmentPerformance::factory()->create();

        // Assert that the department performance model exists
        $this->assertModelExists($department_performance);
        
        // Assert that the department performance was created successfully 
        $this->assertInstanceOf(DepartmentPerformance::class, $department_performance);  

        // Assert that the department performance has a total hours tracker
        $this->assertNotEmpty($department_performance->total_hours);    

        // Assert that the department performance has an SEI average score
        $this->assertNotEmpty($department_performance->sei_avg);  

        // Assert that the department performance has an enrolled average
        $this->assertNotEmpty($department_performance->enrolled_avg);

        // Assert that the department performance has a dropped average
        $this->assertNotEmpty($department_performance->dropped_avg);

        // Assert that the department performance has a capacity average
        $this->assertNotEmpty($department_performance->capacity_avg);

        // Assert that the department performance has an associated year
        $this->assertNotEmpty($department_performance->year);  

        // Assert that the department performance has an associated department
        $this->assertNotEmpty($department_performance->dept_id);
    }

    public function test_department_performance_has_valid_department_id() {
        $department = Department::factory()->create();
        $department_performance = DepartmentPerformance::factory()->create([
            'dept_id' => $department->id
        ]);
        $this->assertEquals($department->id, $department_performance->dept_id);
    }
}

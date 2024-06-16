<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Department;
use App\Models\DepartmentPerformance;
use Illuminate\Support\Facades\Schema;

class DepartmentPerformanceTest extends TestCase
{
    public function test_department_performance_table_exists()
    {
        $this->assertTrue(
            Schema::hasTable('department_performance'),
            'department_performance table does not exist'
        );
    }

    public function test_department_performance_can_be_created()
    {
        $department_performance = DepartmentPerformance::factory()->create();
        $this->assertModelExists($department_performance);
        
    }

    public function test_department_performance_has_valid_department_id(){
        $department = Department::factory()->create();
        $department_performance = DepartmentPerformance::factory()->create([
            'dept_id' => $department->id
        ]);
        $this->assertEquals($department->id, $department_performance->dept_id);
    }
}

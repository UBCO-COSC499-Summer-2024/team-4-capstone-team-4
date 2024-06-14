<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Department;
use Illuminate\Support\Facades\Schema;

class DepartmentTest extends TestCase
{
    public function test_departments_table_exists()
    {
        $this->assertTrue(
            Schema::hasTable('departments'),
            'departments table does not exist'
        );
    }

    public function test_department_can_be_created(): void
    {
        $department = Department::factory()->create();

        // Assert that the department model exists
        $this->assertModelExists($department);

        // Assert that the department was created successfully 
        $this->assertInstanceOf(Department::class, $department);

        // Assert that the department has a name
        $this->assertNotEmpty($department->name);  

    }

}

<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Department;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentTest extends TestCase {
    use RefreshDatabase;
    /**
     * Test if departments table exists.
     *
     * @return void
     */
    public function test_departments_table_exists() {
        $this->assertTrue(
            Schema::hasTable('departments'),
            'departments table does not exist'
        );
    }

    /**
     * Test creating a department.
     *
     * @return void
     */
    public function test_department_can_be_created(): void {
        $department = Department::factory()->create();

        // Assert that the department model exists
        $this->assertModelExists($department);

        // Assert that the department was created successfully 
        $this->assertInstanceOf(Department::class, $department);

        // Assert that the department has a name
        $this->assertNotEmpty($department->name);  

    }

}

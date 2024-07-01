<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Area;
use App\Models\Department;
use Illuminate\Support\Facades\Schema;

class AreaTest extends TestCase {
    /**
     * Test if areas table exists.
     *
     * @return void
     */
    public function test_areas_table_exists() {
        $this->assertTrue(
            Schema::hasTable('areas'),
            'Areas table does not exist'
        );
    }

    /**
     * Test creating an area.
     *
     * @return void
     */
    public function test_area_can_be_created(): void {
        // Create a department instance
        $department = Department::factory()->create();

        // Create an area instance associated with the department
        $area = Area::factory()->create([
            'dept_id' => $department->id
        ]);

        // Assert that the area model exists
        $this->assertModelExists($area);

        // Assert that the area was created successfully 
        $this->assertInstanceOf(Area::class, $area);

        // Assert that the area has a name and dept_id
        $this->assertNotEmpty($area->name); 
        $this->assertNotEmpty($area->dept_id);

    }

    public function test_area_has_valid_dept_id(): void {
        // Create a department instance
        $department = Department::factory()->create();

        // Create an area instance associated with the department
        $area = Area::factory()->create([
            'dept_id' => $department->id
        ]);

        // Retrieve the dept_id from the area
        $id = $area->dept_id;

        // Assert that the dept_id is valid by checking its existence in the departments table
        $this->assertDatabaseHas('departments', [
            'id' => $id
        ]);
    }
}

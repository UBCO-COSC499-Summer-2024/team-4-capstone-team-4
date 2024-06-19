<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\AreaPerformance;
use App\Models\Area;
use App\Models\Department;

class AreaPerformanceTest extends TestCase {
    use RefreshDatabase;

    /**
     * Test if area_performance table exists.
     *
     * @return void
     */
    public function test_area_performance_table_exists() {
        $this->assertTrue(
            Schema::hasTable('area_performance'),
            'Area_performance table does not exist'
        );
    }

    /**
     * Test creating an area performance tracker.
     *
     * @return void
     */
    public function test_areapeformance__can_be_created(): void {
        // Create a department
        $department = Department::factory()->create();

        // Create an area with the associated department
        $area = Area::factory()->create(['dept_id' => $department->id]);

        // Assert that the area model exists
        $this->assertModelExists($area);

        // Assert that the area was created successfully
        $this->assertInstanceOf(Area::class, $area);

        // Assert that the area has a name and dept_id
        $this->assertNotEmpty($area->name);
        $this->assertEquals($department->id, $area->dept_id);
    }

    public function test_area_performance_has_valid_area_id() {
        $area = Area::factory()->create();
        $area_performance = AreaPerformance::factory()->create([
            'area_id' => $area->id
        ]);
        $this->assertEquals($area->id, $area_performance->area_id);
    }
}

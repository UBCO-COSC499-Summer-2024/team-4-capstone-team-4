<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\AreaPerformance;
use App\Models\Area;

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
    public function test_area_performance_can_be_created() {
        // Ensure an area is created before creating AreaPerformance
        $area = Area::factory()->create();

        // Create an area performance
        $area_performance = AreaPerformance::factory()->create([
            'area_id' => $area->id,
        ]);

        // Assert that the area performance model exists
        $this->assertModelExists($area_performance);

        // Assert that the area performance was created successfully 
        $this->assertInstanceOf(AreaPerformance::class, $area_performance);

        // Assert that the area performance has a total hours tracker and is an array
        $this->assertNotEmpty($area_performance->total_hours);  

        // Assert that the area performance has an SEI average score
        $this->assertNotEmpty($area_performance->sei_avg);
        
        // Assert that the area performance has an enrolled average
        $this->assertNotEmpty($area_performance->enrolled_avg);

        // Assert that the area performance has a dropped average
        $this->assertNotEmpty($area_performance->dropped_avg);

        // Assert that the area performance has an associated year
        $this->assertNotEmpty($area_performance->year);  

        // Assert that the area performance has an associated area
        $this->assertNotEmpty($area_performance->area_id);
    }

    public function test_area_performance_has_valid_area_id() {
        // Create an area
        $area = Area::factory()->create();

        // Create an area performance with the associated area
        $area_performance = AreaPerformance::factory()->create([
            'area_id' => $area->id,
        ]);

        $this->assertEquals($area->id, $area_performance->area_id);
    }
}

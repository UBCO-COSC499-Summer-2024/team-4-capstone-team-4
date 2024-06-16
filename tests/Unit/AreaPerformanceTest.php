<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\AreaPerformance;
use App\Models\Area;

class AreaPerformanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the area_performance table exists.
     */
    public function test_area_performance_table_exists()
    {
        $this->assertTrue(
            Schema::hasTable('area_performance'),
            'area_performance table does not exist'
        );
    }

    public function test_area_performance_can_be_created()
    {
        $area_performance = AreaPerformance::factory()->create();
        $this->assertModelExists($area_performance);

    }

    public function test_area_performance_has_valid_area_id(){
        $area = Area::factory()->create();
        $area_performance = AreaPerformance::factory()->create([
            'area_id' => $area->id
        ]);
        $this->assertEquals($area->id, $area_performance->area_id);
    }
}

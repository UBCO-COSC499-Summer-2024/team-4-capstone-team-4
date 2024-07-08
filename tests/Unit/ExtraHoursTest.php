<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use App\Models\UserRole;
use App\Models\ExtraHours;
use App\Models\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExtraHoursTest extends TestCase {
    use RefreshDatabase;
    /**
     * Test if extra_hours table exists.
     *
     * @return void
     */
    public function test_extra_hours_table_exists() {
        $this->assertTrue(
            Schema::hasTable('extra_hours'),
            'Extra_hours table does not exist'
        );
    }

    /**
     * Test creating an extra hours.
     *
     * @return void
     */
    public function test_extra_hours_can_be_created() {
        // Create an assigner & an instructor & an area
        $assigner = UserRole::factory()->create();
        $instructor = UserRole::factory()->create();
        $area = Area::factory()->create();

        // Create an extra hours
        $extraHours = ExtraHours::factory()->create();([
            'assigner_id' => $assigner->id,
            'instructor_id'=> $instructor->id,
            'area_id' => $area->id,

        ]);

        // Assert that the extra hours model exists
        $this->assertModelExists($extraHours);

        // Assert that the extra hours was created successfully 
        $this->assertInstanceOf(ExtraHours::class, $extraHours);

        // Assert that the course section has a name
        $this->assertNotEmpty($extraHours->name);  

        // Assert that the course section has a description
        $this->assertNotEmpty($extraHours->description); 

        // Assert that the course section has hours
        $this->assertNotEmpty($extraHours->hours);  

        // Assert that the course section has a year
        $this->assertNotEmpty($extraHours->year); 
        
        // Assert that the course section has a month
        $this->assertNotEmpty($extraHours->month);  

        // Assert that the course section has an assigner
        $this->assertNotEmpty($extraHours->assigner_id);  

        // Assert that the course section has an instructor
        $this->assertNotEmpty($extraHours->instructor_id); 

        // Assert that the course section has an area
        $this->assertNotEmpty($extraHours->area_id);  
    }
}

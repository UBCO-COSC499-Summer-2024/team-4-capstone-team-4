<?php

namespace Tests\Unit;

use App\Models\CourseSection;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use App\Models\SeiData;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeiDataTest extends TestCase {
    use RefreshDatabase;
    /**
     * Test if sei_data table exists.
     *
     * @return void
     */
    public function test_sei_data_table_exists() {
        $this->assertTrue(
            Schema::hasTable('sei_data'),
            'Sei_data table does not exist'
        );
    }

    /**
     * Test creating an sei data tracker.
     *
     * @return void
     */
    public function test_sei_data_can_be_created() {
        // Create a course section
        $courseSection = CourseSection::factory()->create();

        // Create an sei data
        $seiData = SeiData::factory()->create();([
            'course_Section_id'=> $courseSection->id,
        ]);

        // Assert that the sei data model exists
        $this->assertModelExists($seiData);

        // Assert that the sei data was created successfully 
        $this->assertInstanceOf(SeiData::class, $seiData);

        // Assert that the sei data has a course section
        $this->assertNotEmpty($seiData->course_section_id);  

        // Assert that the role assignment has questions
        $this->assertNotEmpty($seiData->questions);    
    }
}

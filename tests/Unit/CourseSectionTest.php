<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\CourseSection;
use App\Models\Area;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseSectionTest extends TestCase {
    use RefreshDatabase;
    /**
     * Test if course section table exists.
     *
     * @return void
     */
    public function test_course_section_table_exists() {
        $this->assertTrue(
            Schema::hasTable('course_sections'),
            'Course_sections table does not exist'
        );
    }

    /**
     * Test creating a course section.
     *
     * @return void
     */
    public function test_course_section_can_be_created() {
        // Create an area
        $area = Area::factory()->create();

        // Create a course section
        $courseSection = CourseSection::factory()->create();([
            'area_id' => $area->id,
        ]);

        // Assert that the course section model exists
        $this->assertModelExists($courseSection);

        // Assert that the course section was created successfully 
        $this->assertInstanceOf(CourseSection::class, $courseSection);

        // Assert that the course section has a prefix
        $this->assertNotEmpty($courseSection->prefix); 

        // Assert that the course section has a number
        $this->assertNotEmpty($courseSection->number);  

        // Assert that the course section has an area
        $this->assertNotEmpty($courseSection->area); 

        // Assert that the course section has a year
        $this->assertNotEmpty($courseSection->year); 

        // Assert that the course section has an enrolled quantity
        $this->assertNotEmpty($courseSection->enrolled); 

        // Assert that the course section has a dropped quantity
        $this->assertNotEmpty($courseSection->dropped); 

        // Assert that the course section has a capacity
        $this->assertNotEmpty($courseSection->capacity); 

        // Assert that the course section has a year
        $this->assertNotEmpty($courseSection->year); 
    }
}
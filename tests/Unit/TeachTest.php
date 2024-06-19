<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use App\Models\Teach;
use App\Models\CourseSection;
use App\Models\UserRole;

class TeachTest extends TestCase {
    /**
     * Test if teaches table exists.
     *
     * @return void
     */
    public function test_teaches_table_exists() {
        $this->assertTrue(
            Schema::hasTable('teaches'),
            'Teaches table does not exist'
        );
    }

    /**
     * Test creating a teaching tracker.
     *
     * @return void
     */
    public function test_teach_can_be_created() {
        // Create a course section & instructor
        $courseSection = CourseSection::factory()->create();
        $instructor = UserRole::factory()->create();

        // Create a teaching tracker
        $teach = Teach::factory()->create();([
            'course_section_id'=> $courseSection->id,
            'instructor_id' => $instructor->id,
        ]);

        // Assert that the teach model exists
        $this->assertModelExists($teach);

        // Assert that the teach was created successfully 
        $this->assertInstanceOf(Teach::class, $teach);

        // Assert that the teach has an associated course section
        $this->assertNotEmpty($teach->course_section_id);  

        // Assert that the teach has an associated instructor
        $this->assertNotEmpty($teach->instructor_id); 
    }
}

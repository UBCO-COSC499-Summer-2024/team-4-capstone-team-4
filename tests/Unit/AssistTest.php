<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Assist;
use App\Models\CourseSection;
use App\Models\TeachingAssistant;
use Illuminate\Support\Facades\Schema;

class AssistTest extends TestCase {
    use RefreshDatabase; // Ensure database is refreshed before each test

    /**
     * Test if assits table exists.
     *
     * @return void
     */
    public function test_assists_table_exists() {
        $this->assertTrue(
            Schema::hasTable('assists'),
            'Assists table does not exist'
        );
    }

    /**
     * Test retrieving the associated course section for an assist.
     *
     * @return void
     */
    //public function test_course_section_relation()
    //{
        // Create test data
        //$courseSection = CourseSection::factory()->create();
        //$assist = Assist::factory()->create(['course_section_id' => $courseSection->id]);

        // Retrieve the associated course section using the relation
        //$retrievedCourseSection = $assist->courseSection;

        // Assert that the retrieved course section matches the created course section
        //$this->assertInstanceOf(CourseSection::class, $retrievedCourseSection);
        //$this->assertEquals($courseSection->id, $retrievedCourseSection->id);
    //}

    /**
     * Test retrieving the associated teaching assistant for an assist.
     *
     * @return void
     */
    //public function test_teaching_assistant_relation()
    //{
        // Create test data
        //$teachingAssistant = TeachingAssistant::factory()->create();
        //$assist = Assist::factory()->create(['ta_id' => $teachingAssistant->id]);

        // Retrieve the associated teaching assistant using the relation
        //$retrievedTeachingAssistant = $assist->teachingAssistant;

        // Assert that the retrieved teaching assistant matches the created teaching assistant
        //$this->assertInstanceOf(TeachingAssistant::class, $retrievedTeachingAssistant);
        //$this->assertEquals($teachingAssistant->id, $retrievedTeachingAssistant->id);
    //}

    /**
     * Test creating an assist record.
     *
     * @return void
     */
    public function test_assist_can_be_created() {
        // Create a course section and teaching assistant
        $courseSection = CourseSection::factory()->create();
        $teachingAssistant = TeachingAssistant::factory()->create();

        // Create an assist record
        $assist = Assist::factory()->create([
            'course_section_id' => $courseSection->id,
            'ta_id' => $teachingAssistant->id,
            'rating' => 4, // Example rating value
        ]);

        // Assert that the assist model exists
        $this->assertDatabaseHas('assists', [
            'course_section_id' => $courseSection->id,
            'ta_id' => $teachingAssistant->id,
            'rating' => 4,
        ]);
    }

    /**
     * Test updating an assist record.
     *
     * @return void
     */
    public function test_assist_can_be_updated() {
        // Create a course section and teaching assistant
        $courseSection = CourseSection::factory()->create();
        $teachingAssistant = TeachingAssistant::factory()->create();

        // Create an assist record
        $assist = Assist::factory()->create([
            'course_section_id' => $courseSection->id,
            'ta_id' => $teachingAssistant->id,
            'rating' => 4,
        ]);

        // Update the assist record
        $assist->update(['rating' => 5]);

        // Assert that the assist record is updated
        $this->assertEquals(5, $assist->fresh()->rating);
    }
}


<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use App\Models\TeachingAssistant;

class TeachingAssistantTest extends TestCase {
    /**
     * Test if teaching assistant table exists.
     *
     * @return void
     */
    public function test_teaching_assistant_table_exists() {
        $this->assertTrue(
            Schema::hasTable('teaching_assistants'),
            'Teaching_assistants table does not exist'
        );
    }

    /**
     * Test creating a teaching assistant.
     *
     * @return void
     */
    public function test_teaching_assistant_can_be_created() {
        // Create a teaching assistant
        $teachingAssistant = TeachingAssistant::factory()->create();

        // Assert that the teaching assistant model exists
        $this->assertModelExists($teachingAssistant);

        // Assert that the teaching assistant was created successfully 
        $this->assertInstanceOf(TeachingAssistant::class, $teachingAssistant);

        // Assert that the teaching assistant has a name
        $this->assertNotEmpty($teachingAssistant->name);  

        // Assert that the teaching assistant has a rating
        $this->assertNotEmpty($teachingAssistant->rating); 
    }
}

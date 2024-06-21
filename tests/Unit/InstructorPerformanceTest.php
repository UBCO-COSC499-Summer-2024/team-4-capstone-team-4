<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use App\Models\InstructorPerformance;
use App\Models\UserRole;

class InstructorPerformanceTest extends TestCase {
    /**
     * Test if instructor_performance table exists.
     *
     * @return void
     */
    public function test_instructor_performance_table_exists() {
        $this->assertTrue(
            Schema::hasTable('instructor_performance'),
            'Instructor_performance table does not exist'
        );
    }

    /**
     * Test creating an instructor performance tracker.
     *
     * @return void
     */
    public function test_instructor_performance_can_be_created() {
        // Create an instructor
        $instructor = UserRole::factory()->create();

        // Create an instructor performance
        $instructorPerformance = InstructorPerformance::factory()->create();([
            'instructor_id'=> $instructor->id,
        ]);

        // Assert that the instructor_performance model exists
        $this->assertModelExists($instructorPerformance);

        // Assert that the instructor performance was created successfully 
        $this->assertInstanceOf(InstructorPerformance::class, $instructorPerformance);

        // Assert that the instructor performance has a score
        $this->assertNotEmpty($instructorPerformance->score);  

        // Assert that the instructor performance has a total hours tracker
        $this->assertNotEmpty($instructorPerformance->total_hours);  

        // Assert that the instructor performance has an hours target
        $this->assertNotEmpty($instructorPerformance->target_hours);   

        // Assert that the instructor performance has an SEI average score
        $this->assertNotEmpty($instructorPerformance->sei_avg);  

        // Assert that the instructor performance has an associated year
        $this->assertNotEmpty($instructorPerformance->year);  

        // Assert that the instructor performance has an associated instructor
        $this->assertNotEmpty($instructorPerformance->instructor_id); 
    }
}

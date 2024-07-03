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
        $instructor_performance = InstructorPerformance::factory()->create();([
            'instructor_id'=> $instructor->id,
        ]);

        // Assert that the instructor_performance model exists
        $this->assertModelExists($instructor_performance);

        // Assert that the instructor performance was created successfully 
        $this->assertInstanceOf(InstructorPerformance::class, $instructor_performance);

        // Assert that the instructor performance has a score
        $this->assertNotEmpty($instructor_performance->score);  

        // Assert that the instructor performance has a total hours tracker
        $this->assertNotEmpty($instructor_performance->total_hours);  

        // Assert that the instructor performance has an hours target
        $this->assertNotEmpty($instructor_performance->target_hours);   

        // Assert that the instructor performance has an SEI average score
        $this->assertNotEmpty($instructor_performance->sei_avg);  

        // Assert that the instructor performance has an enrolled average
        $this->assertNotEmpty($instructor_performance->enrolled_avg);

        // Assert that the instructor performance has a dropped average
        $this->assertNotEmpty($instructor_performance->dropped_avg);

        // Assert that the instructor performance has a capacity average
        $this->assertNotEmpty($instructor_performance->capacity_avg);

        // Assert that the instructor performance has an associated year
        $this->assertNotEmpty($instructor_performance->year);  

        // Assert that the instructor performance has an associated instructor
        $this->assertNotEmpty($instructor_performance->instructor_id); 
    }

    public function test_instructor_performance_has_valid_instructor_id() {
        // Create an instructor
        $instructor = UserRole::factory()->create(['role' => 'instructor']);

        // Create an instructor performance with the associated instructor
        $instructor_performance = InstructorPerformance::factory()->create([
            'instructor_id' => $instructor->id,
        ]);

        $this->assertEquals($instructor->id, $instructor_performance->instructor_id);
    }
}

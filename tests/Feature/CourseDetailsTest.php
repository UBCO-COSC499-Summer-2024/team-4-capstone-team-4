<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CourseSection;
use App\Models\Area;

class CourseDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_details_page_loads_correctly()
    {
        $area = Area::factory()->create();
        CourseSection::factory()->create([
            'name' => 'Test Course',
            'area_id' => $area->id,
            'duration' => 10,
            'enrolled' => 20,
            'dropped' => 5,
            'capacity' => 30
        ]);

        $response = $this->get('/courses');
        
        $response->assertStatus(200);
        $response->assertSee('Test Course');
        $response->assertSee($area->name);
    }

    public function test_course_edit_functionality()
    {
        $area = Area::factory()->create();
        $courseSection = CourseSection::factory()->create([
            'name' => 'Test Course',
            'area_id' => $area->id,
            'duration' => 10,
            'enrolled' => 20,
            'dropped' => 5,
            'capacity' => 30
        ]);

        $response = $this->post('/course-details/save', [
            'ids' => [$courseSection->id],
            'courseNames' => ['Updated Course'],
            'courseDurations' => [12],
            'enrolledStudents' => [25],
            'droppedStudents' => [3],
            'courseCapacities' => [35]
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('course_sections', [
            'id' => $courseSection->id,
            'name' => 'Updated Course',
            'duration' => 12,
            'enrolled' => 25,
            'dropped' => 3,
            'capacity' => 35
        ]);
    }

    public function test_assign_modal_displays_correctly()
    {
        $response = $this->get('/courses');

        $response->assertStatus(200);
        $response->assertSee('Assign Course');

        // Simulate clicking the button and check for modal content
        $response->assertSee('Coming Soon');
    }
}

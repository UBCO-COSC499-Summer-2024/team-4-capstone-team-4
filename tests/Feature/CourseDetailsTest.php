<?php

namespace Tests\Feature;

use App\Models\CourseSection;
use App\Models\User;
use App\Models\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseDetailsTest extends TestCase{
    use RefreshDatabase;

    public function test_course_details_page_loads_correctly()
    {
        $user = User::factory()->create();
        $area = Area::factory()->create(['name' => 'Computer Science']);
        $courseSection = CourseSection::factory()->create(['area_id' => $area->id, 'name' => 'Test Course']);

        $this->actingAs($user);

        $response = $this->get('/course-details');
        
        $response->assertStatus(200);
        $response->assertSee('Test Course');
        $response->assertSee($area->name);
    }

    public function test_course_edit_functionality()
    {
        $user = User::factory()->create();
        $courseSection = CourseSection::factory()->create([
            'name' => 'Original Course',
            'duration' => 10,
            'enrolled' => 20,
            'dropped' => 2,
            'capacity' => 30,
        ]);

        $this->actingAs($user);

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
            'capacity' => 35,
        ]);
    }

    public function test_assign_modal_displays_correctly()
    {
        $user = User::factory()->create();
        $courseSection = CourseSection::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/course-details');

        $response->assertStatus(200);
        $response->assertSee('Assign Course');

        // Simulate clicking the button and check for modal content
        $response->assertSee('Coming Soon');
    }
}

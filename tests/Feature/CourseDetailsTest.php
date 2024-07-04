<?php

namespace Tests\Feature;

use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\CourseDetailsController;


class CourseDetailsTest extends TestCase{
    use RefreshDatabase;


    public function test_course_edit_functionality(){
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

    public function test_save_method_with_missing_data(){
    $controller = new CourseDetailsController();

    // Mock data with missing fields
    $request = Request::create('/course-details/save', 'POST', [
        'ids' => [1],
        // 'courseNames' => ['Updated Course'], // Missing courseNames
        'courseDurations' => [12],
        'enrolledStudents' => [100],
        'droppedStudents' => [10],
        'courseCapacities' => [120],
    ]);

    $response = $controller->save($request);

    $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
    $data = $response->getData(true);
    $this->assertArrayHasKey('message', $data);
    $this->assertEquals('Course names are required.', $data['message']);
}
}

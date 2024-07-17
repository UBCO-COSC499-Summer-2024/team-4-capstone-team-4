<?php

namespace Tests\Feature;

use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Http\Controllers\CourseDetailsController;

class CourseDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_edit_functionality()
{
    // Ensure the user has the appropriate role to access the save route
    $user = User::factory()->create();
    $user->roles()->create([
        'role' => 'dept_head', // Ensure the user has the 'dept_head' role
    ]);

    $courseSection = CourseSection::factory()->create([
        "id"=> 1,
        "prefix"=> "Updated",
        "number"=>"Course",
        "area_id"=> 1,
        "year"=>2021,
        "enrolled"=> 25,
        "dropped"=>3,
        "capacity"=> 35,
        "term"=> "Fall",
        "session"=> "Regular",
        "section"=>"A"
    ]);

    $this->actingAs($user);

    $response = $this->post('/course-details/save', [
        'ids' => [$courseSection->id],
        'courseNames' => ['Updated Course'],
        'enrolledStudents' => [25],
        'droppedStudents' => [3],
        'courseCapacities' => [35]
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('course_sections', [
        "id"=> 1,
        "prefix"=> "Updated",
        "number"=> "Course",
        "area_id"=>1,
        "year"=> 2021,
        "enrolled"=> 25,
        "dropped"=> 3,
        "capacity"=> 35,
        "term"=> "Fall",
        "session"=> "Regular",
        "section"=> "A"
    ]);
}
    public function test_save_method_with_missing_data()
    {
        $controller = new CourseDetailsController();

        // Mock data with missing fields
        $request = Request::create('/course-details/save', 'POST', [
            'ids' => [1],
            // 'courseNames' => ['Updated Course'], // Missing courseNames
            'enrolledStudents' => [100],
            'droppedStudents' => [10],
            'courseCapacities' => [120],
        ]);

        $response = $controller->save($request);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $data = $response->getData(true);
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('Data arrays are not of the same length.', $data['message']);
    }
}

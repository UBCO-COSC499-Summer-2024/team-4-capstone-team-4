<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\CourseDetailsController;
use App\Models\CourseSection;
use App\Models\SeiData;
use App\Models\User;
use App\Models\Department;
use App\Models\UserRole;
use App\Models\Area;
use Illuminate\Http\Request;

class CourseDetailsUnitTest extends TestCase{
    use RefreshDatabase;

    public function test_calculate_average_rating(){
        $controller = new CourseDetailsController();

        $questionsJson = '{"q1":"3","q2":"4","q3":"5","q4":"2","q5":"2","q6":"4"}';
        $averageRating = $this->callMethod($controller, 'calculateAverageRating', [$questionsJson]);

        $this->assertEquals(3.33, $averageRating);
    }
    public function test_show_method()
    {
        $controller = new CourseDetailsController();

        // Mock data
        $courseSections = collect([
            (object) [
                'id' => 1,
                'name' => 'Course 1',
                'area' => (object) ['name' => 'Computer Science'],
                'duration' => 12,
                'enrolled' => 100,
                'dropped' => 10,
                'capacity' => 120,
            ],
        ]);

        $request = Request::create('/course-details', 'GET');
        $response = $controller->show($request);

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('course-details', $response->name());
        $this->assertArrayHasKey('courseSections', $response->getData());
    }

    public function test_save_method()
    {
        $controller = new CourseDetailsController();

        // Mock data
        $request = Request::create('/course-details/save', 'POST', [
            'ids' => [1],
            'courseNames' => ['Updated Course'],
            'courseDurations' => [12],
            'enrolledStudents' => [100],
            'droppedStudents' => [10],
            'courseCapacities' => [120],
        ]);

        $response = $controller->save($request);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $data = $response->getData(true);
        $this->assertEquals('Courses updated successfully.', $data['message']);
    }
    public function test_show_method_with_empty_course_sections(){
        $controller = new CourseDetailsController();

        // Mock an empty list of course sections
        $courseSections = collect();

        $request = Request::create('/course-details', 'GET');
        $response = $controller->show($request);

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('course-details', $response->name());
        $this->assertArrayHasKey('courseSections', $response->getData());
        $this->assertEmpty($response->getData()['courseSections']);
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
        $this->assertNotEquals('Courses updated successfully.', $data['message']);
    }

    // Helper method to call protected/private methods
    protected function callMethod($obj, $name, array $args){
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method->invokeArgs($obj, $args);
    }
}

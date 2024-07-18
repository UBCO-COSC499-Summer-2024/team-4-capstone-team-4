<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\CourseDetailsController;
use App\Models\CourseSection;
use App\Models\SeiData;
use App\Models\User;
use App\Models\Area;
use Illuminate\Http\Request;

class CourseDetailsUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_average_rating()
    {
        $controller = new CourseDetailsController();

        $questionsJson = '{"q1":"3","q2":"4","q3":"5","q4":"2","q5":"2","q6":"4"}';
        $averageRating = $this->callMethod($controller, 'calculateAverageRating', [$questionsJson]);

        $this->assertEquals(3.33, $averageRating);
    }

    public function test_calculate_average_rating_with_empty_questions()
    {
        $controller = new CourseDetailsController();
        $questionsJson = json_encode([]);
        $expectedAverage = 0.00;
        $actualAverage = $this->callMethod($controller, 'calculateAverageRating', [$questionsJson]);
        $this->assertEquals($expectedAverage, $actualAverage);
    }

    public function test_show_method()
    {
        $controller = new CourseDetailsController();

        // Create mock data
        $user = User::factory()->create();
        $area = Area::factory()->create();
        $courseSection = CourseSection::factory()->create([
            'prefix' => 'CS',
            'number' => '101',
            'area_id' => $area->id,
            'year' => 2022,
            'enrolled' => 100,
            'dropped' => 10,
            'capacity' => 120,
            'term' => 'Fall',
            'session' => 'A',
            'section' => '001',
        ]);

        $request = Request::create('/course-details', 'GET');
        $response = $controller->show($request, $user);

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $data = $response->getData();
        $this->assertArrayHasKey('courseSections', $data);
        $this->assertNotEmpty($courseSection);
    }

    public function test_show_method_with_empty_course_sections()
    {
        $controller = new CourseDetailsController();

        // Create mock data
        $user = User::factory()->create();

        $request = Request::create('/course-details', 'GET');
        $response = $controller->show($request, $user);

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $data = $response->getData();
        $this->assertArrayHasKey('courseSections', $data);
        $this->assertEmpty($data['courseSections']);
    }

    public function test_save_method()
    {
        $controller = new CourseDetailsController();

        // Create mock data
        $area = Area::factory()->create();
        $courseSection = CourseSection::factory()->create([
            'prefix' => 'CS',
            'number' => '101',
            'area_id' => $area->id,
            'year' => 2022,
            'enrolled' => 100,
            'dropped' => 10,
            'capacity' => 120,
            'term' => 'Fall',
            'session' => 'A',
            'section' => '001',
        ]);

        $request = Request::create('/course-details/save', 'POST', [
            'ids' => [$courseSection->id],
            'courseNames' => ['Updated Course'],
            'enrolledStudents' => [25],
            'droppedStudents' => [3],
            'courseCapacities' => [35],
        ]);

        $response = $controller->save($request);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $data = $response->getData(true);
        $this->assertEquals('Courses updated successfully.', $data['message']);
        $this->assertDatabaseHas('course_sections', [
            'id' => $courseSection->id,
            'enrolled' => 25,
            'dropped' => 3,
            'capacity' => 35,
        ]);
    }

    // Helper method to call protected/private methods
    protected function callMethod($obj, $name, array $args)
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method->invokeArgs($obj, $args);
    }
}

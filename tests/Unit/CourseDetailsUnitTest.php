<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\CourseDetailsController;
use App\Models\CourseSection;
use App\Models\Area;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CourseDetailsUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $deptHeadUser;
    protected $instructorUser;
    protected $courseSection;

    public function setUp(): void
    {
        parent::setUp();

        // Create users and assign roles
        $this->adminUser = User::factory()->create();
        UserRole::create(['user_id' => $this->adminUser->id, 'role' => 'admin']);
        
        $this->deptHeadUser = User::factory()->create();
        UserRole::create(['user_id' => $this->deptHeadUser->id, 'role' => 'dept_head']);
        
        $this->instructorUser = User::factory()->create();
        UserRole::create(['user_id' => $this->instructorUser->id, 'role' => 'instructor']);

        // Create course sections and associate with instructor
        $area = Area::factory()->create();
        $this->courseSection = CourseSection::factory()->create(['area_id' => $area->id]);
        $this->courseSection->teaches()->create(['instructor_id' => $this->instructorUser->id]);
    }

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

    public function test_fetch_course_sections_for_instructor()
    {
        $controller = new CourseDetailsController();
        $request = Request::create('/course-details', 'GET');

        try {
            // Simulate fetching course sections for the instructor
            $response = $controller->show($request, $this->instructorUser);
            $this->fail("Expected HttpException not thrown");
        } catch (HttpException $e) {
            $this->assertEquals(403, $e->getStatusCode());
        }
    }

    public function test_handle_no_course_sections_available()
    {
        // Ensure no course sections exist
        CourseSection::query()->delete();

        $controller = new CourseDetailsController();
        $request = Request::create('/course-details', 'GET');

        try {
            // Simulate fetching course sections for the instructor
            $response = $controller->show($request, $this->instructorUser);
            $this->fail("Expected HttpException not thrown");
        } catch (HttpException $e) {
            $this->assertEquals(403, $e->getStatusCode());
        }
    }

    public function test_save_method()
    {
        $this->actingAs($this->deptHeadUser);

        $controller = new CourseDetailsController();

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

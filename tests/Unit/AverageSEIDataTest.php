<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AverageSEIDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_average_rating()
    {
        $controller = new \App\Http\Controllers\CourseDetailsController();

        $questionsJson = '{"q1":"3","q2":"4","q3":"5","q4":"2","q5":"2","q6":"4"}';
        $averageRating = $this->invokeMethod($controller, 'calculateAverageRating', [$questionsJson]);

        $this->assertEquals(3.33, $averageRating);
    }

    // Helper method to call protected/private methods
    protected function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}

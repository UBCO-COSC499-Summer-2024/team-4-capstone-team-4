<?php

namespace App\Http\Controllers;

use App\Models\CourseSection;
use Illuminate\Http\Request;
use App\Models\SeiData;
use App\Models\User;
use App\Models\Teach;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CourseDetailsController extends Controller {

    public function show(Request $request){
        $courseSections = CourseSection::with('area')->get()->map(function ($section, $index) {
            $seiData = SeiData::where('course_section_id', $section->id)->first();
            $averageRating = $seiData ? $this->calculateAverageRating($seiData->questions) : 0;
    
            return (object) [
                'id' => $section->id,
                'serialNumber' => $index + 1,
                'name' => $section->name,
                'departmentName' => $section->area ? $section->area->name : 'Unknown',
                'duration' => $section->duration,
                'enrolled' => $section->enrolled,
                'dropped' => $section->dropped,
                'capacity' => $section->capacity,
                'averageRating' => $averageRating,
            ];
        });
    
        // Fetch additional data needed by the view
        $courses = CourseSection::all(); // Adjust query as needed
        $instructors = User::all(); // Adjust query as needed
    
        $sortField = 'courseName';
        $sortDirection = 'asc';
    
        return view('course-details', compact('courseSections', 'courses', 'instructors', 'sortField', 'sortDirection'));
    }
    


    public function save(Request $request){
        $ids = $request->input('ids', []);
        $courseNames = $request->input('courseNames', []);
        $courseDurations = $request->input('courseDurations', []);
        $enrolledStudents = $request->input('enrolledStudents', []);
        $droppedStudents = $request->input('droppedStudents', []);
        $courseCapacities = $request->input('courseCapacities', []);

        $updatedSections = [];

        if (empty($courseNames)) {
            return response()->json(['message' => 'Course names are required.'], 400);
        }

        for ($i = 0; $i < count($ids); $i++) {
            $courseSection = CourseSection::find($ids[$i]);
            if ($courseSection) {
                $courseSection->name = $courseNames[$i];
                $courseSection->duration = (int)$courseDurations[$i];
                $courseSection->enrolled = (int)$enrolledStudents[$i];
                $courseSection->dropped = (int)$droppedStudents[$i];
                $courseSection->capacity = (int)$courseCapacities[$i];
                $courseSection->save();

                $updatedSections[] = $courseSection; // Collect updated sections for response
            }
        }

        return response()->json([
            'message' => 'Courses updated successfully.',
            'updatedSections' => $updatedSections
        ]);
    }

    public function assignCourse(Request $request) {
       $courseId = $request->input('course_id');
    $instructorId = $request->input('instructor_id');

        $courseSection = CourseSection::find($request->course_id);
        $courseSection->instructor_id = $request->instructor_id;
        $courseSection->save();

        Teach::create([
            'course_section_id' => $courseId,
            'user_id' => $instructorId
        ]);
    
        return redirect()->route('course-details')->with('success', 'Course assigned to instructor successfully.');
    }
    private function calculateAverageRating($questionsJson) {
        $questions = json_decode($questionsJson, true);
        if (is_array($questions) && !empty($questions)) {
            $ratings = array_filter(array_values($questions), function($value) {
                return is_numeric($value);
            });
            $averageRating = count($ratings) > 0 ? array_sum($ratings) / count($ratings) : 0;
            return round($averageRating, 2);
        }
        return 0;
    }
}

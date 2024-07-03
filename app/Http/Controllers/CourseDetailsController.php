<?php

namespace App\Http\Controllers;

use App\Models\CourseSection;
use Illuminate\Http\Request;

class CourseDetailsController extends Controller {

    public function show(Request $request){
        $courseSections = CourseSection::with('area')->get()->map(function ($section, $index) {
            return (object) [
                'id' => $section->id,
                'serialNumber' => $index + 1,
                'name' => $section->name,
                'departmentName' => $section->area ? $section->area->name : 'Unknown',
                'duration' => $section->duration,
                'enrolled' => $section->enrolled,
                'dropped' => $section->dropped,
                'capacity' => $section->capacity,
            ];
        });

        $sortField = 'courseName';
        $sortDirection = 'asc';
        return view('course-details', compact('courseSections', 'sortField', 'sortDirection'));
    }

    public function save(Request $request)
    {
        $ids = $request->input('ids', []);
        $courseNames = $request->input('courseNames', []);
        $courseDurations = $request->input('courseDurations', []);
        $enrolledStudents = $request->input('enrolledStudents', []);
        $droppedStudents = $request->input('droppedStudents', []);
        $courseCapacities = $request->input('courseCapacities', []);

        $updatedSections = [];

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
}

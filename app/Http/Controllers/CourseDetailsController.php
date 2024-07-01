<?php


namespace App\Http\Controllers;

use App\Models\CourseSection;
use Illuminate\Http\Request;

class CourseDetailsController extends Controller{

    public function show(Request $request){
        $courseSections = CourseSection::with('area')->get()->map(function ($section, $index) {
            return (object) [
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
    $serialNumbers = $request->input('serialNumbers', []);
    $courseNames = $request->input('courseNames', []);
    $departmentNames = $request->input('departmentNames', []);
    $courseDurations = $request->input('courseDurations', []);
    $enrolledStudents = $request->input('enrolledStudents', []);
    $droppedStudents = $request->input('droppedStudents', []);
    $courseCapacities = $request->input('courseCapacities', []);

    for ($i = 0; $i < count($ids); $i++) {
        $courseSection = CourseSection::find($ids[$i]);
        if ($courseSection) {
            $courseSection->serialNumber = $serialNumbers[$i];
            $courseSection->name = $courseNames[$i];
            $courseSection->departmentName = $departmentNames[$i];
            $courseSection->duration = $courseDurations[$i];
            $courseSection->enrolled = $enrolledStudents[$i];
            $courseSection->dropped = $droppedStudents[$i];
            $courseSection->capacity = $courseCapacities[$i];
            $courseSection->save();
        }
    }

    return redirect()->route('course-details')->with('status', 'Data updated successfully!');
}
}
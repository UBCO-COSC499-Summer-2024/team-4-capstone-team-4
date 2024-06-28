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
    
    public function update(Request $request){
    $data = $request->input('data');

    foreach ($data as $item) {
        $section = CourseSection::find($item['id']);
        if ($section) {
            $section->update([
                'name' => $item['courseName'],
                'duration' => $item['courseDuration'],
                'enrolled' => $item['enrolledStudents'],
                'dropped' => $item['droppedStudents'],
                'capacity' => $item['courseCapacity']
            ]);
        }
    }

    return response()->json(['success' => true]);
}
}
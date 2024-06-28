<?php


namespace App\Http\Controllers;

use App\Models\CourseSection;
use Illuminate\Http\Request;

class CourseDetailsController extends Controller{

    public function show(Request $request)
    {
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
}
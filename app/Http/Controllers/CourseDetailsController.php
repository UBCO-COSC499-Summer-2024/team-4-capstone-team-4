<?php


namespace App\Http\Controllers;
use App\Models\CourseSection;
use Illuminate\Http\Request;

class CourseDetailsController extends Controller{

    public function show(Request $request, $instructor_id){
    
        $sortField = $request->input('sort', 'name'); 
        $sortDirection = $request->input('direction', 'asc'); 
        $query = $request->input('search-course', '');

        if (empty($query)) {
            $courses = CourseSection::whereHas('teaches', function($query) use ($instructor_id) {
                $query->where('instructor_id', $instructor_id);
            })
            ->orderBy($sortField, $sortDirection)
            ->get();
        } else {
            $courses = CourseSection::whereHas('teaches', function($query) use ($instructor_id) {
                $query->where('instructor_id', $instructor_id);
            })
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('duration', 'like', "%{$query}%");
            })
            ->orderBy($sortField, $sortDirection)
            ->get();
        }

        $tableData = $courses->map(function($course) {
            return [
                'Column 1' => $course->name,
                'Column 2' => $course->duration,
                'Column 3' => $course->enrolled,
                'Column 4'=>  $course->dropped,
                'Column 5'=>  $course->capacity
            ];
        });

        return view('Course-details', compact('courses', 'tableData', 'query', 'sortField', 'sortDirection'));
    }
}
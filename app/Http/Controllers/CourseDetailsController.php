<?php


namespace App\Http\Controllers;
use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Http\Request;

class CourseDetailsController extends Controller{

    public function show($id, Request $request){

            $courses = CourseSection::whereHas('teaches', function($query) use ($id) {
                $query->where('instructor_id', $id);
            })->get(); 

        $tableData = $courses->map(function($course) {
            return [
                'Course Name' => $course->name,
                'Course Duration' => $course->duration,
                'Enrolled Students' => $course->enrolled,
                'Dropped Students'=>  $course->dropped,
                'Course Capacity'=>  $course->capacity
            ];
        })->toArray();
        
        if($request->ajax()){
            return response()->ajax($tableData);
    }

        $users=User::all();
        return view('Course-details', compact('courses', 'tableData', 'users'));
    }
}
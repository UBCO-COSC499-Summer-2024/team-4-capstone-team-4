<?php


namespace App\Http\Controllers;
use App\Models\CourseSection;
use App\Models\Teach;
use App\Models\User;
use Illuminate\Http\Request;

class CourseDetailsController extends Controller{

    public function show($instructor_id=null){
    
        $courses=[];
        $tableData=[];
        
        if ($instructor_id) {
            $courses = CourseSection::whereHas('teaches', function($query) use ($instructor_id) {
                $query->where('instructor_id', $instructor_id);
            })
            ->get();
        } 

        $tableData = $courses->map(function($course) {
            return [
                'Course Name' => $course->name,
                'Course Duration' => $course->duration,
                'Enrolled Students' => $course->enrolled,
                'Dropped Students'=>  $course->dropped,
                'Course Capacity'=>  $course->capacity
            ];
        })->toArray();
        $users=User::all();

        return view('Course-details', compact('courses', 'tableData', 'users'));
    }
}
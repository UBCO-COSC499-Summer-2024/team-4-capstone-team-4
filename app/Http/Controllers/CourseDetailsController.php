<?php


namespace App\Http\Controllers;

use App\Models\CourseSection;
use Illuminate\Http\Request;

class CourseDetailsController extends Controller{

    public function show(Request $request)
    {
        $courseSection=CourseSection::all();
        
        $sortField = 'courseName';
        $sortDirection = 'asc';

        \Log::info($courseSection);



        return view('course-details', compact('courseSection','sortField','sortDirection'));
        

    } 
}
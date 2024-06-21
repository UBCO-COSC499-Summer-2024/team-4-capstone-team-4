<?php


namespace App\Http\Controllers;
use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Http\Request;

class CourseDetailsController extends Controller{

    public function show()
    {
        $users = User::all();
        $tableData = []; // Initial empty data for the table
        return view('course-details', compact('users', 'tableData'));
    }
}
<?php


namespace App\Http\Controllers;

use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Http\Request;

class CourseDetailsController extends Controller{

    public function show(Request $user_id)
    {
       /* $user = User::with(['teaches.courseSection'])->find($user_id);
        $tableData = []; // Initial empty data for the table
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
        return view('course-details', compact('user', 'tableData')); */

        $user = (object)[
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'profile_photo_url' => 'https://randomuser.me/api/portraits/men/1.jpg'
        ];

        $courseSections = [
            (object)[
                'serialNumber'=>'1',
                'name' => 'COSC 499',
                'department'=>'Computer Science',
                'duration' => '14 weeks',
                'enrolled' => 30,
                'dropped' => 2,
                'capacity' => 40
            ],
            (object)[
                'serialNumber'=>'2',
                'name' => 'COSC 414',
                'department'=>'Computer Science',
                'duration' => '14 weeks',
                'enrolled' => 25,
                'dropped' => 3,
                'capacity' => 35
            ],
            (object)[
                'serialNumber'=>'3',
                'name' => 'MATH 303',
                'department'=>'Mathematics',
                'duration' => '14 weeks',
                'enrolled' => 28,
                'dropped' => 1,
                'capacity' => 30
            ]
        ];

        $sortField = 'courseName';
        $sortDirection = 'asc';

        return view('course-details', compact('user', 'courseSections','sortField','sortDirection'));


    } 
}
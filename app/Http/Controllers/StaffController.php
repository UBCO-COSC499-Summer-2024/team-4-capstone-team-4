<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{

    public function index_search(Request $request)
    {
        // Extract sort parameters
        $sortField = $request->input('sort', 'firstname');
        $sortDirection = $request->input('direction', 'asc');
        
        // Extract search and areas parameters
        $query = $request->input('search-staff', '');
        $areas = $request->input('areas', []);
        //dd($areas);

        // Start building the query to fetch instructors
        $usersQuery = User::query();

        // Apply conditions based on whether search query and areas are provided
        if (empty($query) && !empty($areas)) {
            $usersQuery->whereHas('roles', function ($queryBuilder) {
                    $queryBuilder->where('role', 'instructor');
                })
                ->whereHas('teaches.courseSection.area', function ($queryBuilder) use ($areas) {
                    $queryBuilder->whereIn('name', $areas);
                });
        } elseif (!empty($query) && empty($areas)) {
            $usersQuery->whereHas('roles', function ($queryBuilder) {
                    $queryBuilder->where('role', 'instructor');
                })
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('firstname', 'like', "%{$query}%")
                                ->orWhere('lastname', 'like', "%{$query}%")
                                ->orWhere('email', 'like', "%{$query}%");
                });
        } elseif (!empty($query) && !empty($areas)) {
            $usersQuery->whereHas('roles', function ($queryBuilder) {
                    $queryBuilder->where('role', 'instructor');
                })
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('firstname', 'like', "%{$query}%")
                                ->orWhere('lastname', 'like', "%{$query}%")
                                ->orWhere('email', 'like', "%{$query}%");
                })
                ->whereHas('teaches.courseSection.area', function ($queryBuilder) use ($areas) {
                    $queryBuilder->whereIn('name', $areas);
                });
        } else {
            // Default case: when both $query and $areas are empty
            $usersQuery->whereHas('roles', function ($queryBuilder) {
                    $queryBuilder->where('role', 'instructor');
                });
        }
        //join all the tables
        $usersQuery = $usersQuery->join('user_roles', 'users.id', '=', 'user_roles.user_id')
        ->leftJoin('teaches', 'user_roles.id', '=', 'teaches.instructor_id')
        ->leftJoin('course_sections', 'teaches.course_section_id', '=', 'course_sections.id')
        ->leftJoin('areas', 'course_sections.area_id', '=', 'areas.id')
        ->leftJoin('instructor_performance', 'user_roles.id', '=', 'instructor_performance.instructor_id');

        // Retrieve the users (instructors) and sort according to sort fields
        if ($sortField === 'firstname') {
            $usersQuery = $usersQuery->orderBy('firstname', $sortDirection);
        } elseif ($sortField === 'area') {
            $usersQuery = $usersQuery->orderBy('areas.name', $sortDirection);
        } elseif ($sortField === 'total_hours') {
            $usersQuery = $usersQuery->orderBy('instructor_performance.total_hours', $sortDirection);
        } elseif ($sortField === 'target_hours') {
            $usersQuery = $usersQuery->orderBy('instructor_performance.target_hours', $sortDirection);
        } else {
            $usersQuery = $usersQuery->orderBy('instructor_performance.score', $sortDirection);
        }
        
        $users = $usersQuery->distinct()->get();
        //dd($users);

        return view('staff', compact('users', 'query', 'areas', 'sortField', 'sortDirection'));
    }

    public function add_target_hours(Request $request){
        $request->validate([
           'hours' =>['required', 'numeric'],
           'staff-checkboxes'=>['required']
        ]);
        $hours = $request->input('hours');
        $staff_checkboxes = $request->input('staff-checkboxes');
        //dd($staff_checkboxes);
        foreach($staff_checkboxes as $email){
            $user = User::where('email', $email)->first();
            $instructor = $user->roles->where('role', 'instructor')->first();
            $performance = $instructor->instructorPerformance;
            if($performance){
                $performance->update(['target_hours' => $hours]);
            }else{
                return response()->json(['message' => 'Instructor performance not found.'], 404);
            }
        }

        return redirect('staff');

    }

    public function update_target_hours(Request $request){
        $email = $request->input('emails'); 
        dd($email);
    }
   
}

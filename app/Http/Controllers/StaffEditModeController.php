<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;

class StaffEditModeController extends Controller
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
                })
                ->distinct()
                ->orderBy($sortField, $sortDirection);
        } elseif (!empty($query) && empty($areas)) {
            $usersQuery->whereHas('roles', function ($queryBuilder) {
                    $queryBuilder->where('role', 'instructor');
                })
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('firstname', 'like', "%{$query}%")
                                ->orWhere('lastname', 'like', "%{$query}%")
                                ->orWhere('email', 'like', "%{$query}%");
                })
                ->orderBy($sortField, $sortDirection);
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
                })
                ->distinct()
                ->orderBy($sortField, $sortDirection);
        } else {
            // Default case: when both $query and $areas are empty
            $usersQuery->whereHas('roles', function ($queryBuilder) {
                    $queryBuilder->where('role', 'instructor');
                })
                ->orderBy($sortField, $sortDirection);
        }

        // Retrieve the users (instructors)
        $users = $usersQuery->get();
        //dd($users);

        return view('staff-edit-mode', compact('users', 'query', 'areas', 'sortField', 'sortDirection'));
    }

    public function update_target_hours(Request $request){
       /*  $request->validate([
           'hours' =>['required', 'numeric']
        ]); */
        $hours = $request->input('changedInputs');
       // $length = count($hours);
        $email = $request->input('emails');
       
        dd($hours);
        /* for($i = 0; $i< $length; $i++){
            $user = User::where('email', $email[$i])->first();
            $instructor = $user->roles->where('role', 'instructor')->first();
            $performance = $instructor->instructorPerformance;
            if($performance){
                $performance->update(['target_hours' => $hours]);
            }else{
                return response()->json(['message' => 'Instructor performance not found.'], 404);
            }
        } */

        return redirect('staff');

    }
   
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StaffController extends Controller
{
   /*  public function index(Request $request){

        //make default firstname ascending
        $sortField = $request->input('sort', 'firstname'); 
        $sortDirection = $request->input('direction', 'asc'); 

        $users = User::orderBy($sortField, $sortDirection)->get();

        return view('staff', compact('users', 'sortField', 'sortDirection'));
    } */

    public function index_search(Request $request){

        $sortField = $request->input('sort', 'firstname'); 
        $sortDirection = $request->input('direction', 'asc'); 
        $query = $request->input('search-staff','');
        $areas = $request->input('areas', []);
        //dd($query);

      /*   if(empty($query) || empty($areas)){
            $users = User::orderBy($sortField, $sortDirection)->get();
        }elseif(!empty($query) && empty($areas)){
            $users = User::where('firstname', 'like', "%{$query}%")
            ->orWhere('lastname', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orderBy($sortField, $sortDirection)
            ->get();
        }elseif(empty($query) && !empty($areas)){
            $users = User::whereHas('roles', function($queryy) use ($areas) {
                $queryy->whereIn('area_id', $areas);
            })->get();
        } */
        $usersQuery = User::query();

        // Apply search query if it's not empty
        if (!empty($query)) {
            $usersQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('firstname', 'like', "%{$query}%")
                             ->orWhere('lastname', 'like', "%{$query}%")
                             ->orWhere('email', 'like', "%{$query}%");
            });
        }
    
        // Apply area filters if areas are selected
        if (!empty($areas)) {
            $usersQuery->whereHas('roles', function ($queryBuilder) use ($areas) {
                $queryBuilder->whereIn('area_id', $areas);
            });
        }
    
        // Order the results based on sort field and direction
        $usersQuery->orderBy($sortField, $sortDirection);
    
        // Retrieve the users
        $users = $usersQuery->get();
        
        return view('staff', compact('users', 'query', 'areas', 'sortField', 'sortDirection'));
    }
}

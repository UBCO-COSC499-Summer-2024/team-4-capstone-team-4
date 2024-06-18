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
        //dd($query);

        if(empty($query)){
            $users = User::orderBy($sortField, $sortDirection)->get();
        }else{
            $users = User::where('firstname', 'like', "%{$query}%")
            ->orWhere('lastname', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orderBy($sortField, $sortDirection)
            ->get();
        }
        
        return view('staff', compact('users', 'query', 'sortField', 'sortDirection'));
    }
}

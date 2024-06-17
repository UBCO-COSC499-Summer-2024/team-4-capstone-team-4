<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StaffController extends Controller
{
    public function index(){
        $users = User::all();
        return view('/staff', ['users' => $users]);
    }
}

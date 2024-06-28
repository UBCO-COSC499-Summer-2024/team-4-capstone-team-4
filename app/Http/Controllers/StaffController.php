<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function add_target_hours(Request $request){
        $request->validate([
            'hours' => ['required', 'numeric', 'min:0'],
            'staff-checkboxes' => ['required', 'array', 'min:1']
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
   
}

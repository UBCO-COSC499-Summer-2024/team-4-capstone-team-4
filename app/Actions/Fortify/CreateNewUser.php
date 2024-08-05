<?php

namespace App\Actions\Fortify;

use App\Models\Approval;
use App\Models\ApprovalStatus;
use App\Models\ApprovalType;
use App\Models\User;
use App\Models\UserRole;
use App\Models\InstructorPerformance;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,strict', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        // Create a new user
        $user = User::create([
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // create new Approval for Approval Type registration
        $approvalType = ApprovalType::where('name', 'registration')->first();
        $approvalStatus = ApprovalStatus::where('name', 'pending')->first();
        $approval = Approval::create([
            'user_id' => $user->id,
            'approval_type_id' => $approvalType->id,
            'status_id' => $approvalStatus->id,
            'details' => $user->firstname . ' ' . $user->lastname . ' has requested access to ' . config('app.name'),
        ]);

        Approval::audit('create', [
            'operation_type' => 'CREATE',
            'new_value' => json_encode($approval),
        ], 'New user registration request.');

        // Create the user role
        // $user_role = UserRole::create([
        //     'user_id' => $user->id,
        //     'department_id' => null,
        //     'role' => 'instructor',
        // ]);

        // InstructorPerformance::create([
        //     'instructor_id'=> $user_role->id,
        //     'score' => 0,
        //     'total_hours' => json_encode([
        //         'January' => 0,
        //         'February' => 0,
        //         'March' => 0,
        //         'April' => 0,
        //         'May' => 0,
        //         'June' => 0,
        //         'July' => 0,
        //         'August' => 0,
        //         'September' => 0,
        //         'October' => 0,
        //         'November' => 0,
        //         'December' => 0,
        //     ]),
        //     'target_hours' => null,
        //     'sei_avg' => 0,
        //     'enrolled_avg'=> 0,
        //     'dropped_avg'=> 0,
        //     'year' => date("Y"),
        // ]);

        return $user;
    }
}

<?php

namespace App\Actions\Fortify;

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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        // Create a new user
        $user = User::create([
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // Create the user role
        $user_role = UserRole::create([
            'user_id' => $user->id,
            'department_id' => null,
            'role' => 'instructor',
        ]);

        InstructorPerformance::create([
            'instructor_id'=> $user_role->id,
            'score' => 0,
            'total_hours' => 0,
            'target_hours' => null,
            'sei_avg' => 0,
            'year' => date("Y"),
        ]);

        return $user;
    }
}

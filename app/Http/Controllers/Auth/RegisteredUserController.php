<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\RegisterResponse;
use Illuminate\Http\Request;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterViewResponse;
use Laravel\Fortify\Http\Controllers\RegisteredUserController as FortifyRegisteredUserController;

class RegisteredUserController extends FortifyRegisteredUserController
{
    public function store(Request $request, CreatesNewUsers $creator): RegisterResponse
    {
        event(new Registered($user = $creator->create($request->all())));

        // return redirect()->route('login')->with('status', 'Registration successful. Please login to continue.');
        // return RegisterResponse and status message
        // return app(RegisterResponse::class, [
        //     'status' => 'Registration successful. Your account is awaiting approval.',
        //     'user' => $user
        // ]);
        // dd($user);
        return app(RegisterResponse::class, [
            'user' => $user
        ]);

    }
}

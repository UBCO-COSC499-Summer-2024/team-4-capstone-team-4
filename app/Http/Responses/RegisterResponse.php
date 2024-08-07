<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Fortify;

class RegisterResponse implements RegisterResponseContract {

    public function toResponse($request) {
        return redirect()->route('login')->with('status', 'Registration successful. Your account is awaiting approval.');
    }

}

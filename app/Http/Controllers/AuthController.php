<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\AuthMethod;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function redirectToProvider($provider) {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider) {
        $socialUser = Socialite::driver($provider)->user();
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            // firstname, lastname
            ['firstname' => $socialUser->get]
        );

    }
}

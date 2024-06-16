<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuthMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        try {
            $validProviders = ['google'];

            if (!in_array($provider, $validProviders)) {
                return redirect()->route('login')->with('provider-error', 'Invalid auth provider.');
            }
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('provider-error', 'An error occurred while authenticating with ' . ucfirst($provider) . '.');
        }
    }

    public function handleProviderCallback($provider) {
        try {
            $socialiteUser = Socialite::driver($provider)->user();
            if (auth()->check() && AuthMethod::where('provider', $provider)->where('provider_id', $socialiteUser->getId())->exists()) {
                return redirect()->route('dashboard');
            }
            $user = User::where('email', $socialiteUser->getEmail())->first();

            $name = explode(' ', $socialiteUser->getName() ?? '');
            $firstname = $name[0] ?? '';
            $lastname = $name[1] ?? '';
        
            if (!$user) {
                $user = User::create([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $socialiteUser->getEmail(),
                    'password' => bcrypt(Config::get('app.default_password')),
                    'email_verified_at' => now(),
                ]);
        
                AuthMethod::create([
                    'user_id' => $user->id,
                    'provider' => $provider,
                    'provider_id' => $socialiteUser->getId(),
                    'token' => $socialiteUser->token,
                ]);
                
            } else {
                // If a user is found, update or create the authentication method
                AuthMethod::updateOrCreate([
                    'user_id' => $user->id,
                    'provider' => $provider,
                ], [
                    'provider_id' => $socialiteUser->getId(),
                    'token' => $socialiteUser->token,
                ]);
            }
            
            if ($socialiteUser->getAvatar()) {
                AuthMethod::where('user_id', $user->id)
                          ->where('provider', $provider)
                          ->update(['avatar' => $socialiteUser->getAvatar()]);
            }
        
            Auth::login($user, true);
            return redirect()->intended('/dashboard');
        } catch(\Exception $e) {
            return redirect()->route('login')->with('provider-error', 'An error occurred while authenticating with ' . ucfirst($provider) . '.  '. $e->getMessage());
        }
    }
}

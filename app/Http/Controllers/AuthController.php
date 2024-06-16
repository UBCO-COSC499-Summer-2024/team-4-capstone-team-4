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
            // Log::info("Socialite Authentication Initiated: Provider: " . $provider);
            // Log::info("Socialite Authentication Initiated: Client ID: " . Config::get('services.' . $provider . '.client_id'));
            // Log::info("Socialite Authentication Initiated: Redirect URL: " . Config::get('services.' . $provider . '.redirect'));
            $validProviders = ['google'];

            if (!in_array($provider, $validProviders)) {
                return redirect()->route('login')->with('provider-error', 'Invalid auth provider.');
            }
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            // Log::error("Socialite Authentication Error: " . $e->getMessage());
            return redirect()->route('login')->with('provider-error', 'An error occurred while authenticating with ' . ucfirst($provider) . '.');
        }
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialiteUser = Socialite::driver($provider)->user();
            // Log::info("Socialite Authentication Callback: " . $socialiteUser);
            $user = User::where('user_id', $socialiteUser->getId())->first();
            // Log::info("Socialite Authentication Callback: User: " . $user);

            $name = explode(' ', $socialiteUser->getName() ?? '');

            $firstname = $name[0] ?? '';
            $lastname = $name[1] ?? '';
        
            if (!$user) {
                $user = User::create([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $socialiteUser->getEmail(),
                    'email_verified_at' => now(),
                ]);
        
                AuthMethod::create([
                    'user_id' => $user->id,
                    'provider' => $provider,
                    'provider_id' => $socialiteUser->getId(),
                    'token' => $socialiteUser->token,
                ]);
                
            } else {
                AuthMethod::updateOrCreate([
                    'user_id' => $user->id,
                    'provider' => $provider,
                ], [
                    'provider_id' => $socialiteUser->getId(),
                    'token' => $socialiteUser->token,
                ]);
            }
            
            if ($socialiteUser->getAvatar()) {
                AuthMethod::where('user_id', $user->id)->update(['avatar' => $socialiteUser->getAvatar()]);
            }
        
            auth()->login($user, true);
            return redirect()->intended('/');
        } catch(\Exception $e) {
            // Log::error("Socialite Authentication Error: " . $e->getMessage());
            return redirect()->route('login')->with('provider-error', 'An error occurred while authenticating with ' . ucfirst($provider) . '.');
        }
    }
}
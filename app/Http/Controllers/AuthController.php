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
                AuthMethod::where('user_id', auth()->id())
                          ->where('provider', $provider)
                          ->update([
                              'token' => $socialiteUser->token,
                              'avatar' => $socialiteUser->getAvatar() ?? null, // Set to null if avatar is not present
                          ]);

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
                    'profile_photo_path' => $socialiteUser->getAvatar() ?? null,
                ]);

                $authmethods = AuthMethod::create([
                    'user_id' => $user->id,
                    'provider' => $provider,
                    'provider_id' => $socialiteUser->getId(),
                    'token' => $socialiteUser->token,
                    'avatar' => $socialiteUser->getAvatar() ?? null,
                ]);

                $user->settings()->create([
                    'theme' => 'light',
                    'locale' => 'en',
                    'language' => 'en',
                    'timezone' => 'UTC',
                    'auth_method_id' => $authmethods->id,
                ]);

            } else {
                AuthMethod::updateOrCreate([
                    'user_id' => $user->id,
                    'provider' => $provider,
                ], [
                    'provider_id' => $socialiteUser->getId(),
                    'token' => $socialiteUser->token,
                    'avatar' => $socialiteUser->getAvatar() ?? null,
                ]);
                // if user profile photo is not set, update it
                if (!$user->profile_photo_path) {
                    $user->update([
                        'profile_photo_path' => $socialiteUser->getAvatar() ?? null,
                    ]);
                }

                $user->settings()->updateOrCreate([
                    'user_id' => $user->id,
                ], [
                    'auth_method_id' => $user->authmethods->where('provider', $provider)->first()->id,
                ]);
            }

            Auth::login($user, true);
            return redirect()->intended('/dashboard');
        } catch(\Exception $e) {
            return redirect()->route('login')->with('provider-error', 'An error occurred while authenticating with ' . ucfirst($provider) . '.  '. $e->getMessage());
        }
    }
}

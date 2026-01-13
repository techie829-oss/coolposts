<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Unable to login via ' . ucfirst($provider) . '. Please try again.']);
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Update existing user with social ID if not present
            if ($provider === 'google' && !$user->google_id) {
                $user->update(['google_id' => $socialUser->getId()]);
            } elseif ($provider === 'linkedin' && !$user->linkedin_id) {
                $user->update(['linkedin_id' => $socialUser->getId()]);
            }

            // Update avatar if not present or needs update (optional policy, here we just set it if we have a field for it)
            // The User model has 'social_avatar' as per my migration plan.
            if (!$user->social_avatar) {
                $user->update(['social_avatar' => $socialUser->getAvatar()]);
            }

            Auth::login($user);

            return redirect()->intended(route('dashboard', absolute: false));
        } else {
            // Create new user
            // We need to handle the password. Since it's social login, we can generate a random password.
            // Users can reset it later if they want to login via email/password.

            $userData = [
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(), // Assume social login emails are verified
                'social_avatar' => $socialUser->getAvatar(),
            ];

            if ($provider === 'google') {
                $userData['google_id'] = $socialUser->getId();
            } elseif ($provider === 'linkedin') {
                $userData['linkedin_id'] = $socialUser->getId();
            }

            // Handle referral code from session if exists (assuming standard referral logic)
            // Checking how RegisteredUserController handles it.
            // It explicitly checks request input. For social login, we might lose that context unless we pass it through state or session.
            // I'll check session for 'referral_code' if I was managing it there, but standard flow usually puts it in URL.
            // For now, I'll simple create the user. If referral is critical, I might need to look at how to persist it across the social redirect.
            // But let's stick to basic creation first.

            $user = User::create($userData);

            // Trigger Registered event? 
            // event(new \Illuminate\Auth\Events\Registered($user)); 
            // Maybe yes, to send welcome email etc.

            Auth::login($user);

            return redirect()->intended(route('dashboard', absolute: false));
        }
    }
}

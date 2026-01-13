<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.unified-login');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referral_code' => ['nullable', 'string', 'max:20'],
        ]);

        // Handle referral code if provided
        $referredBy = null;
        if ($request->filled('referral_code')) {
            $globalSettings = \App\Models\GlobalSetting::getSettings();
            if ($globalSettings->isReferralsEnabled()) {
                $referralCode = strtoupper(trim($request->referral_code));
                $referrer = User::where('referral_code', $referralCode)->first();
                if ($referrer) {
                    $referredBy = $referrer->id;
                } else {
                    return back()->withErrors(['referral_code' => 'Invalid referral code. Please check and try again.'])->withInput();
                }
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'referred_by' => $referredBy,
        ]);

        // Process referral signup bonus if user was referred
        $referralService = app(\App\Services\ReferralCommissionService::class);
        $referralService->processSignupBonus($user);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

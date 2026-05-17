<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\SignupOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

class SignupWizardController extends Controller
{
    // Step 1: Email
    public function step1()
    {
        return view('auth.register-step1');
    }

    public function postStep1(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        ]);

        $otp = sprintf("%06d", mt_rand(100000, 999999));
        Cache::put('signup_otp_' . $request->email, $otp, now()->addMinutes(15));
        
        Mail::to($request->email)->send(new SignupOtpMail($otp));

        session()->put('signup_email', $request->email);

        return redirect()->route('register.step2');
    }

    // Step 2: OTP
    public function step2()
    {
        if (!session()->has('signup_email')) {
            return redirect()->route('register');
        }

        return view('auth.register-step2', ['email' => session('signup_email')]);
    }

    public function postStep2(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = session('signup_email');
        if (!$email) {
            return redirect()->route('register');
        }

        $cachedOtp = Cache::get('signup_otp_' . $email);

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            return back()->withErrors(['otp' => 'The provided verification code is invalid or has expired.']);
        }

        session()->put('signup_email_verified', true);
        Cache::forget('signup_otp_' . $email);

        return redirect()->route('register.step3');
    }

    // Step 3: Name & Password
    public function step3()
    {
        if (!session('signup_email_verified')) {
            return redirect()->route('register.step2');
        }

        return view('auth.register-step3', ['email' => session('signup_email')]);
    }

    public function postStep3(Request $request)
    {
        if (!session('signup_email_verified')) {
            return redirect()->route('register.step2');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = session('signup_email');

        // Auto-generate unique username
        $baseUsername = Str::slug(explode('@', $email)[0], '_');
        $baseUsername = preg_replace('/[^A-Za-z0-9_]/', '', $baseUsername); // Ensure alphanumeric and underscore
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'username' => $username,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Auto verify since they did OTP
        ]);

        event(new Registered($user));
        Auth::login($user);

        session()->forget(['signup_email', 'signup_email_verified']);

        return redirect(route('home', absolute: false));
    }
}

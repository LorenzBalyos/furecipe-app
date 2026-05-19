<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm() {
        return view('auth.register');
    }

    public function processRegistration(Request $request)
    {
        // 1. Strict Request Validation Check
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::min(8)->letters()->numbers()->symbols()],
        ]);

        // 2. Generate a random secure 6-digit numeric OTP
        $otpCode = rand(100000, 999999);

        // 3. Stash user data securely into Session Cache for 15 minutes
        session([
            'registration_data' => [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ],
            'registration_otp' => $otpCode,
            'otp_expires_at' => now()->addMinutes(15)
        ]);

        // 4. Fire the OTP directly to their inbox
        Mail::raw("Your Furecipe verification code is: {$otpCode}. It will expire in 15 minutes.", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Verify Your Furecipe Registration 🐾');
        });

        // 5. Take them to the authentication keypad input card layout
        return redirect('/register/verify-otp');
    }

    public function showOtpForm() {
        if (!session()->has('registration_data')) {
            return redirect('/register');
        }
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);

        // Check if data exists or has timed out
        if (!session()->has('registration_data') || now()->gt(session('otp_expires_at'))) {
            session()->forget(['registration_data', 'registration_otp', 'otp_expires_at']);
            return redirect('/register')->withErrors(['email' => 'Your validation window expired. Please try again.']);
        }

        // Verify matches exactly
        if ($request->otp !== (string)session('registration_otp')) {
            return redirect('/register/verify-otp')->with('error', 'The security code you entered is invalid.');
        }
// OTP is legitimate! Pull user parameters and save them officially to DB
$userData = session('registration_data');
$user = User::create([
    'name' => $userData['name'],
    'email' => $userData['email'],
    'password' => $userData['password'],
]);

// Authenticate user session directly (Bypasses the broken guard helper method)
session([
    'user_email' => $user->email,
    'user_name'  => $user->name
]);

// Clear remaining security session variables cleanly
session()->forget(['registration_data', 'registration_otp', 'otp_expires_at']);

// Send directly to verified mobile application frame home dashboard
return redirect('/dashboard?tab=home');
    }
}

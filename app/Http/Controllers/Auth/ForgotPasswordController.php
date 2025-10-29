<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function sendOtp(Request $request)
    {
        $email = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(5);

        // Save OTP in users table
        $user->otp = json_encode([
            'code' => $otp,
            'expires_at' => $expiresAt->toDateTimeString()
        ]);
        $user->save();

        try {
            // Send OTP via email
            Mail::raw("Your password reset OTP is: {$otp}. It will expire in 5 minutes.", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Password Reset OTP');
            });
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to send OTP email. Try again later.'], 500);
        }

        Log::info("OTP sent to {$user->email}: {$otp}");

        return response()->json([
            'message' => 'OTP sent successfully to your email',
            'email' => $user->email
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !$user->otp) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $otpData = json_decode($user->otp, true);

        if ($otpData['code'] != $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        if (Carbon::now()->gt(Carbon::parse($otpData['expires_at']))) {
            return response()->json(['message' => 'OTP expired'], 400);
        }

        return response()->json(['message' => 'OTP verified successfully']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !$user->otp) {
            return response()->json(['message' => 'Invalid request'], 400);
        }


        $user->password = Hash::make($request->password);
        $user->otp = null; // clear OTP
        $user->save();

        return response()->json(['message' => 'Password reset successful']);
    }
}

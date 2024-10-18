<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{


    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        // Cek posisi user, hanya marketing dan operational yang boleh melakukan forgot password
        if (!in_array($user->position, ['marketing', 'operational'])) {
            return response()->json([
                'message' => 'Email not found'
            ], 403);
        }

        // Generate OTP
        $otp = rand(1000, 9999);
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10); // OTP berlaku 10 menit
        $user->save();

        // Kirim OTP ke email
        Mail::to($user->email)->send(new OtpMail($otp));

        return response()->json(['message' => 'OTP sent to email']);
    }


    // Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|numeric'
        ]);

        $user = User::where('email', $request->email)
                    ->where('otp_code', $request->otp_code)
                    ->first();

        if (!$user || Carbon::now()->gt($user->otp_expires_at)) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        return response()->json(['message' => 'OTP verified']);
    }

    // Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|numeric',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::where('email', $request->email)
                    ->where('otp_code', $request->otp_code)
                    ->first();

        if (!$user || Carbon::now()->gt($user->otp_expires_at)) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        // Set password baru
        $user->password = Hash::make($request->password);
        $user->otp_code = null; // Clear OTP setelah berhasil reset
        $user->otp_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Password reset successfully']);
    }

    // Login method
    public function login(Request $request)
    {   
        // Validasi input
        $request->validate([
            'login' => 'required|string', // Bisa email atau username
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan email atau username
        $user = User::where('email', $request->login)
                    ->orWhere('username', $request->login)
                    ->first();

        // Jika user tidak ditemukan atau password salah
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Cek posisi user, hanya marketing dan operational yang boleh login
        if (!in_array($user->position, ['marketing', 'operational'])) {
            return response()->json([
                'message' => 'Unauthorized: Only marketing and operational can login.'
            ], 403);
        }

        // Jika validasi berhasil, buat token baru
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return token dan data user
        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    // Logout method
    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}

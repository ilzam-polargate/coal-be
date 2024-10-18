<?php

namespace App\Http\Controllers;

use App\Models\User; // Sekarang digunakan secara eksplisit
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function showProfile()
    {
        return view('pages.user-profile');
    }

    public function updateProfile(Request $request)
    {
        // Validasi input form
        $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|string|email|max:250|unique:users,email,' . auth()->id(),
            'hp' => 'required|string|max:250',
            'password' => 'nullable|string|min:8|confirmed', // Konfirmasi password
        ]);

        // Ambil user yang sedang login secara eksplisit
        $user = User::findOrFail(auth()->id()); // Sekarang langsung dari model User

        // Data yang akan di-update
        $data = [
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'hp' => $request->input('hp'),
        ];

        // Jika ada perubahan password, lakukan hashing
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        try {
            // Gunakan metode update tanpa perlu save()
            $user->update($data);

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile.');
        }
    }
}
